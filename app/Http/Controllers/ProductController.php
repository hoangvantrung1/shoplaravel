<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $categoryName = null;
        $brandName = null;
        $carouselProducts = Product::where('is_hot', 1)->take(8)->get();
        $newProducts = Product::latest()->take(4)->get();
        // Lọc theo danh mục (nếu có)
        if ($request->filled('category')) {
            $category = Category::find($request->input('category'));
            if ($category) {
                $query->where('category_id', $category->id);
                $categoryName = $category->name;
            }
        }

        // Lọc theo thương hiệu (nếu có)
        if ($request->filled('brand_id')) {
            $brand = Brand::find($request->input('brand_id'));
            if ($brand) {
                $query->where('brand_id', $brand->id);
                $brandName = $brand->name;
            }
        }

        // Tìm kiếm theo từ khóa q (nhiều từ): tên, mô tả, slug, brand, category
        $search = trim((string) $request->input('q'));
        if ($search !== '') {
            $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($terms as $term) {
                $like = "%{$term}%";
                $query->where(function ($sub) use ($like) {
                    $sub->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('slug', 'like', $like)
                        ->orWhereHas('brand', function ($b) use ($like) {
                            $b->where('name', 'like', $like);
                        })
                        ->orWhereHas('category', function ($c) use ($like) {
                            $c->where('name', 'like', $like);
                        });
                });
            }
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();

        // Pass all necessary variables to the view
        return view('products.index', compact('products', 'categories', 'categoryName', 'carouselProducts', 'newProducts', 'brands', 'brandName'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('products.show', compact('product'));
    }
}
