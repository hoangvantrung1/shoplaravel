<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Danh sách sản phẩm
    public function index()
    {
        $products = Product::orderBy('created_at', 'asc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    // Form tạo mới
    public function create()
    {
        $categories = Category::all(); // Lấy danh mục để select
        return view('admin.products.create', compact('categories'));
    }

    // Lưu sản phẩm mới
  public function store(Request $request)
{
    // Chuẩn hóa is_hot
    $requestData = $request->all();
    $requestData['is_hot'] = $request->has('is_hot');

    // Validate
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    // Tạo slug tự động từ tên
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $counter = 1;
    while (Product::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter++;
    }
    $requestData['slug'] = $slug;

    // Upload ảnh
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $imageName);
        $requestData['image'] = 'uploads/' . $imageName;
    }

    Product::create($requestData);

    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
}

    // Form chỉnh sửa
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Cập nhật sản phẩm
 public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Chuẩn hóa dữ liệu
    $requestData = $request->all();
    $requestData['is_hot'] = $request->has('is_hot');

    // Tạo slug tự động từ name
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $counter = 1;

    while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
        $slug = $originalSlug . '-' . $counter++;
    }
    $requestData['slug'] = $slug;

    // Validate
    $request->merge($requestData);
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'is_hot' => 'required|boolean',
        'slug' => 'required|string|unique:products,slug,' . $product->id,
    ]);

    // Upload ảnh nếu có
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $imageName);
        $requestData['image'] = 'uploads/' . $imageName;
    }

    // Update sản phẩm
    $product->update($requestData);

    return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
}
    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh cũ nếu có
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
