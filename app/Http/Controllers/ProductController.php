<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $categoryName = null;
        $carouselProducts = Product::where('is_hot', 1)->take(8)->get();
        $newProducts = Product::latest()->take(4)->get();
        if ($request->has('category')) {
            $category = Category::find($request->input('category'));
            if ($category) {
                $query->where('category_id', $category->id);
                $categoryName = $category->name;
            }
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        // Pass all necessary variables to the view
        return view('products.index', compact('products', 'categories', 'categoryName', 'carouselProducts', 'newProducts'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('products.show', compact('product'));
    }
}
