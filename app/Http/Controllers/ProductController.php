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
        $categories = Category::all();
        $brands = Brand::all();
        $query = Product::query();
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
        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
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

        // Phân trang
        $products = $query->orderBy('id', 'asc')->paginate(12)->withQueryString();

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
        $newProductsQuery = Product::query();
        $newProducts = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $featuredProductsQuery = Product::query();
        $featuredProducts = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

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
        $reviewsCount = $product->reviews->count();

        $averageRating = round($product->reviews->avg('rating'), 1);

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
            'averageRating'
        ));
    }
}