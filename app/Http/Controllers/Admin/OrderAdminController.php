<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderShipped;
use App\Mail\OrderCompleted;

class OrderAdminController extends Controller
{
    // Danh sách đơn hàng
    public function index()
    {
        $orders = Order::with('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // Chi tiết đơn hàng
    public function show(Order $order)
    {
        $order->load('orderItems.product');
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
        ], [
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ'
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

        // CẬP NHẬT TRẠNG THÁI
        $order->update([
            'status' => $newStatus,
            'updated_at' => now()
        ]);

        // Log cập nhật thành công
        Log::info('Cập nhật trạng thái đơn hàng thành công', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'user' => auth()->user()->name ?? 'Admin'
        ]);

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
}