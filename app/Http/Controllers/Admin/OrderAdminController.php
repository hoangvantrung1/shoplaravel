<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderNote;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderShipped;
use App\Mail\OrderCompleted;
use App\Mail\OrderDelivered;

class OrderAdminController extends Controller
{
    /**
     * Danh sách đơn hàng với bộ lọc nâng cao
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems');

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Lọc theo trạng thái thanh toán (paid/unpaid)
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->whereIn('status', ['paid', 'confirmed', 'processing', 'shipping', 'delivered', 'completed']);
            } elseif ($request->payment_status === 'unpaid') {
                $query->whereIn('status', ['unpaid', 'pending']);
            }
        }

        // Lọc theo khoảng thời gian
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tìm kiếm theo mã đơn, tên, email, SĐT
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        if (in_array($sortBy, ['created_at', 'total', 'status'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng với timeline và ghi chú
     */
    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'notes.admin']);
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái
    public function updateStatus(Request $request, Order $order)
    {
        // Các trạng thái được phép
        $allowedStatuses = [
            'unpaid', 'pending', 'confirmed', 'processing', 
            'shipping', 'delivered', 'completed', 'cancelled'
        ];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'note' => 'nullable|string|max:1000', // Ghi chú nội bộ (tùy chọn)
        ], [
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'note.max' => 'Ghi chú không được vượt quá 1000 ký tự',
        ]);

        $oldStatus = strtolower(trim($order->status));
        $newStatus = strtolower(trim($request->status));
        
        // KIỂM TRA: Không cho thay đổi đơn đã hoàn thành, đã hủy hoặc thất bại
        if (in_array($oldStatus, ['completed', 'cancelled', 'failed'])) {
            return back()->with('error', 'Không thể thay đổi trạng thái đơn hàng đã kết thúc!');
        }

        // KIỂM TRA: Logic chuyển đổi trạng thái hợp lệ
        if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
            // Log để debug
            Log::warning('Chuyển trạng thái không hợp lệ', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            
            return back()->with('error', 'Không thể chuyển từ trạng thái "' . $this->getStatusLabel($oldStatus) . '" sang "' . $this->getStatusLabel($newStatus) . '"!');
        }

        // LƯU TRẠNG THÁI CŨ để xử lý hoàn tồn kho
        $wasNotCancelled = ($oldStatus !== 'cancelled');

        // CẬP NHẬT TRẠNG THÁI VÀ LƯU GHI CHÚ trong transaction
        DB::beginTransaction();
        try {
            // CẬP NHẬT TRẠNG THÁI
            $order->update([
                'status' => $newStatus,
                'updated_at' => now()
            ]);

            // Lưu ghi chú nếu có
            if ($request->filled('note')) {
                OrderNote::create([
                    'order_id' => $order->id,
                    'note' => $request->note,
                    'status' => $newStatus,
                    'is_internal' => true,
                    'created_by' => auth('admin')->id(),
                ]);
            } else {
                // Tự động tạo ghi chú khi thay đổi trạng thái
                OrderNote::create([
                    'order_id' => $order->id,
                    'note' => "Trạng thái đã thay đổi từ '{$this->getStatusLabel($oldStatus)}' sang '{$this->getStatusLabel($newStatus)}'",
                    'status' => $newStatus,
                    'is_internal' => true,
                    'created_by' => auth('admin')->id(),
                ]);
            }

            // Log cập nhật thành công
            Log::info('Cập nhật trạng thái đơn hàng thành công', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_id' => auth('admin')->id(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật trạng thái đơn hàng: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái!');
        }

        // GỬI EMAIL theo trạng thái
        $this->sendStatusEmail($order, $newStatus);

        // HOÀN TỒN KHO nếu hủy đơn
        if ($newStatus === 'cancelled' && $wasNotCancelled) {
            $this->restoreStock($order);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Kiểm tra logic chuyển đổi trạng thái có hợp lệ không
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            'unpaid' => ['paid', 'confirmed', 'cancelled'],
            'paid' => ['pending', 'confirmed', 'cancelled'],
            'pending' => ['confirmed', 'processing', 'cancelled'],
            'confirmed' => ['processing', 'shipping', 'cancelled'],
            'processing' => ['shipping', 'delivered', 'cancelled'],
            'shipping' => ['delivered', 'cancelled'],
            'delivered' => ['completed'],
            'completed' => [],
            'cancelled' => [],
            'failed' => [],
        ];

        // Kiểm tra xem có trong danh sách chuyển đổi hợp lệ không
        if (!isset($validTransitions[$oldStatus])) {
            return false;
        }

        return in_array($newStatus, $validTransitions[$oldStatus]);
    }

    /**
     * Gửi email thông báo theo trạng thái
     */
    private function sendStatusEmail(Order $order, $status)
    {
        try {
            switch ($status) {
                case 'confirmed':
                    // Có thể gửi email xác nhận đơn hàng
                    // Mail::to($order->customer_email)->send(new OrderConfirmed($order));
                    break;
                    
                case 'processing':
                case 'shipping':
                    Mail::to($order->customer_email)->send(new OrderShipped($order));
                    break;
                    
                case 'delivered':
                    Mail::to($order->customer_email)->send(new OrderDelivered($order));
                    break;

                case 'completed':
                    Mail::to($order->customer_email)->send(new OrderCompleted($order));
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email trạng thái đơn hàng: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            // Không throw exception để không làm gián đoạn việc cập nhật trạng thái
        }
    }

    /**
     * Hoàn trả tồn kho khi hủy đơn
     */
    private function restoreStock(Order $order)
    {
        try {
            foreach ($order->orderItems as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    
                    if ($product) {
                        // Hoàn trả số lượng vào kho
                        $product->increment('stock', (int) $item->quantity);
                        
                        Log::info('Hoàn tồn kho sản phẩm', [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $item->quantity,
                            'new_stock' => $product->stock
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Lỗi hoàn tồn kho: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy nhãn hiển thị của trạng thái
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'unpaid' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'failed' => 'Thất bại',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Xóa đơn hàng (optional - nếu cần)
     */
    public function destroy(Order $order)
    {
        try {
            // Chỉ cho phép xóa đơn đã hủy hoặc hoàn thành
            if (!in_array($order->status, ['cancelled', 'completed'])) {
                return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy hoặc hoàn thành!');
            }

            $order->delete();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Đã xóa đơn hàng thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi xóa đơn hàng: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xóa đơn hàng!');
        }
    }

    /**
     * Thêm ghi chú nội bộ cho đơn hàng
     */
    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
            'is_internal' => 'boolean',
        ], [
            'note.required' => 'Vui lòng nhập ghi chú',
            'note.max' => 'Ghi chú không được vượt quá 1000 ký tự',
        ]);

        try {
            OrderNote::create([
                'order_id' => $order->id,
                'note' => $request->note,
                'status' => $order->status,
                'is_internal' => $request->has('is_internal') ? (bool)$request->is_internal : true,
                'created_by' => auth('admin')->id(),
            ]);

            Log::info('Thêm ghi chú đơn hàng', [
                'order_id' => $order->id,
                'admin_id' => auth('admin')->id(),
            ]);

            return redirect()->back()->with('success', 'Thêm ghi chú thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi thêm ghi chú đơn hàng: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi thêm ghi chú!');
        }
    }
}