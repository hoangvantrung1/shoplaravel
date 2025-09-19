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
        if ($request->has('category')) {
            $category = Category::find($request->input('category'));
            if ($category) {
                $query->where('category_id', $category->id);
                $categoryName = $category->name;
            }
        }

        // Lọc theo thương hiệu (nếu có)
        if ($request->has('brand_id')) {
            $brand = Brand::find($request->input('brand_id'));
            if ($brand) {
                $query->where('brand_id', $brand->id);
                $brandName = $brand->name;
            }
        }

        // Tìm kiếm theo từ khóa q (tên, mô tả)
        $search = trim((string) $request->input('q'));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
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
