<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
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
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'deal_start_date' => 'nullable|date',
            'deal_end_date' => 'nullable|date|after:deal_start_date',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'deal_end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
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

        // Xử lý sale_price: nếu để trống hoặc = 0 thì set null
        if (empty($request->sale_price) || (float) $request->sale_price == 0) {
            $requestData['sale_price'] = null;
        } else {
            $requestData['sale_price'] = (float) $request->sale_price;
        }
        
        // Xử lý deal_start_date và deal_end_date
        $requestData['deal_start_date'] = $request->deal_start_date ? $request->deal_start_date : null;
        $requestData['deal_end_date'] = $request->deal_end_date ? $request->deal_end_date : null;

        Product::create($requestData);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    // Form chỉnh sửa
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'deal_start_date' => 'nullable|date',
            'deal_end_date' => 'nullable|date|after:deal_start_date',
            'stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'deal_end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        // is_hot checkbox
        $validated['is_hot'] = $request->has('is_hot');

        // Slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $validated['slug'] = $slug;

        // Upload ảnh nếu có
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $imageName);
            $validated['image'] = 'uploads/' . $imageName;
        }

        // Xử lý sale_price: nếu để trống hoặc = 0 thì set null
        if (empty($request->sale_price) || (float) $request->sale_price == 0) {
            $validated['sale_price'] = null;
        } else {
            $validated['sale_price'] = (float) $request->sale_price;
        }
        
        // Xử lý deal_start_date và deal_end_date
        $validated['deal_start_date'] = $request->deal_start_date ? $request->deal_start_date : null;
        $validated['deal_end_date'] = $request->deal_end_date ? $request->deal_end_date : null;

        // Lưu giá trị cũ để so sánh
        $oldPrice = $product->price;
        $oldStock = $product->stock;
        $oldSalePrice = $product->sale_price;

        // Cập nhật sản phẩm trong transaction
        DB::beginTransaction();
        try {
            $product->update($validated);

            // Log thay đổi giá nếu có
            if ($oldPrice != $validated['price']) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'field_changed' => 'price',
                    'old_value' => $oldPrice,
                    'new_value' => $validated['price'],
                    'changed_by' => auth('admin')->id(),
                    'notes' => $request->input('price_note', null),
                ]);

                Log::info('Thay đổi giá sản phẩm', [
                    'product_id' => $product->id,
                    'old_price' => $oldPrice,
                    'new_price' => $validated['price'],
                    'admin_id' => auth('admin')->id(),
                ]);
            }

            // Log thay đổi tồn kho nếu có
            if ($oldStock != $validated['stock']) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'field_changed' => 'stock',
                    'old_value' => $oldStock,
                    'new_value' => $validated['stock'],
                    'changed_by' => auth('admin')->id(),
                    'notes' => $request->input('stock_note', null),
                ]);

                Log::info('Thay đổi tồn kho sản phẩm', [
                    'product_id' => $product->id,
                    'old_stock' => $oldStock,
                    'new_stock' => $validated['stock'],
                    'admin_id' => auth('admin')->id(),
                ]);
            }

            // Log thay đổi giá khuyến mãi nếu có
            if ($oldSalePrice != $validated['sale_price']) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'field_changed' => 'sale_price',
                    'old_value' => $oldSalePrice ?? 0,
                    'new_value' => $validated['sale_price'] ?? 0,
                    'changed_by' => auth('admin')->id(),
                    'notes' => $request->input('sale_price_note', null),
                ]);

                Log::info('Thay đổi giá khuyến mãi sản phẩm', [
                    'product_id' => $product->id,
                    'old_sale_price' => $oldSalePrice,
                    'new_sale_price' => $validated['sale_price'],
                    'admin_id' => auth('admin')->id(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật sản phẩm: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm!');
        }

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

    /**
     * Xem lịch sử thay đổi của sản phẩm
     */
    public function logs($id)
    {
        $product = Product::findOrFail($id);
        $logs = ProductLog::where('product_id', $product->id)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.products.logs', compact('product', 'logs'));
    }

    /**
     * Danh sách deal khuyến mãi đang chạy / sắp diễn ra / đã kết thúc
     */
    public function deals(Request $request)
    {
        $now = Carbon::now();
        $status = $request->get('status', 'active');

        $baseQuery = Product::with(['category', 'brand'])
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->whereColumn('sale_price', '<', 'price');

        $activeQuery = (clone $baseQuery)
            ->where(function ($query) use ($now) {
                $query->whereNull('deal_start_date')
                    ->orWhere('deal_start_date', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('deal_end_date')
                    ->orWhere('deal_end_date', '>=', $now);
            });

        $upcomingQuery = (clone $baseQuery)
            ->whereNotNull('deal_start_date')
            ->where('deal_start_date', '>', $now);

        $expiredQuery = (clone $baseQuery)
            ->whereNotNull('deal_end_date')
            ->where('deal_end_date', '<', $now);

        $stats = [
            'active' => (clone $activeQuery)->count(),
            'upcoming' => (clone $upcomingQuery)->count(),
            'expired' => (clone $expiredQuery)->count(),
        ];
        $stats['all'] = array_sum($stats);

        switch ($status) {
            case 'upcoming':
                $productsQuery = $upcomingQuery;
                break;
            case 'expired':
                $productsQuery = $expiredQuery;
                break;
            case 'all':
                $productsQuery = $baseQuery;
                break;
            case 'active':
            default:
                $status = 'active';
                $productsQuery = $activeQuery;
                break;
        }

        $products = $productsQuery
            ->orderByRaw('COALESCE(deal_start_date, created_at) ASC')
            ->paginate(12)
            ->appends($request->query());

        return view('admin.products.deals', [
            'products' => $products,
            'stats' => $stats,
            'status' => $status,
            'now' => $now,
        ]);
    }
}
