<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Load categories với số lượng sản phẩm
        $categories = Category::withCount('products')->get();
        $brands = Brand::all();
        // Eager load relationships để tránh N+1 query
        $query = Product::with(['category', 'brand']);
        $posts = Post::latest()->paginate(6);

        // Lọc theo tìm kiếm
        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->get('brand_id'));
        }
        // Lọc theo giá - FIXED
        if ($request->filled('min_price')) {
            $minPrice = (float) $request->min_price;
            $query->where('price', '>=', $minPrice);
        }
        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->max_price;
            $query->where('price', '<=', $maxPrice);
        }
        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['name', 'price', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Phân trang - giữ nguyên query string khi phân trang
        $products = $query->orderBy('id', 'asc')->paginate(12)->appends($request->query());

        // $products = $query->paginate(12)->withQueryString();

        // Xác định tên danh mục và thương hiệu để hiển thị
        $categoryName = null;
        $brandName = null;

        if ($request->filled('category')) {
            $category = Category::find($request->get('category'));
            $categoryName = $category ? $category->name : null;
        }

        if ($request->filled('brand_id')) {
            $brand = Brand::find($request->get('brand_id'));
            $brandName = $brand ? $brand->name : null;
        }
        // Eager load relationships để tránh N+1 query
        // Lấy sản phẩm mới nhất
        $newProducts = Product::with(['category', 'brand'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('stock', '>', 0) // Chỉ lấy sản phẩm còn hàng
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Featured products - sản phẩm nổi bật (có thể là best sellers hoặc có rating cao)
        $featuredProducts = Product::with(['category', 'brand'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('stock', '>', 0) // Chỉ lấy sản phẩm còn hàng
            ->orderBy('reviews_avg_rating', 'desc')
            ->orderBy('reviews_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($product) {
                // Tính discount nếu có sale_price
                if ($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price) {
                    $product->discount = round((1 - $product->sale_price / $product->price) * 100);
                } else {
                    $product->discount = 0;
                }
                return $product;
            });

        $isHomePage = !$request->hasAny(['q', 'category', 'brand_id', 'min_price', 'max_price']);
        $stockStats = [
            'total_products' => Product::count(),
            'in_stock' => Product::where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
        ];

        return view('products.index', compact(
            'products',
            'newProducts',
            'featuredProducts',
            'categories',
            'brands',
            'categoryName',
            'brandName',
            'isHomePage',
            'stockStats',
            'posts'
        ));
    }

    public function show($id)
    {
        $product = Product::with([
            'category',
            'brand',
            'reviews' => function ($query) {
                $query->where('is_approved', true)->with('user');
            }
        ])->findOrFail($id);
        // Lấy reviews từ product và gán vào biến riêng
        $reviews = $product->reviews;

        $reviewsCount = $reviews->count();

        $averageRating = round($product->reviews->avg('rating'), 1);

        // Tính rating breakdown (số lượng review theo từng sao)
        $ratingBreakdown = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        $relatedProducts = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->limit(4)
            ->get();
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'categories',
            'brands',
            'reviewsCount',
            'averageRating',
            'reviews',
            'ratingBreakdown'
        ));
    }
}