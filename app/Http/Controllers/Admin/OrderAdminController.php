<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Mail\OrderCompleted;

class OrderAdminController extends Controller
{
    // Danh sách đơn hàng
    public function index()
    {
        $orders = Order::orderBy('id', 'asc')->paginate(10);
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
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        if ($oldStatus === 'cancelled') {
            return back()->with('error', 'Không thể thay đổi trạng thái đơn hàng đã hủy!');
        }

        // CHẶN: Không cho thay đổi đơn đã hoàn thành
        if ($oldStatus === 'completed') {
            return back()->with('error', 'Không thể thay đổi trạng thái đơn hàng đã hoàn thành!');
        }

        // CHẶN: Không cho hủy đơn đã hoàn thành
        if ($request->status === 'cancelled' && $oldStatus === 'completed') {
            return back()->with('error', 'Không thể hủy đơn hàng đã hoàn thành!');
        }
        $order->update(['status' => $request->status]);

        // Gửi email theo trạng thái
        try {
            if ($request->status === 'processing') {
                Mail::to($order->customer_email)->send(new OrderShipped($order));
            }
            if ($request->status === 'completed') {
                Mail::to($order->customer_email)->send(new OrderCompleted($order));
            }
        } catch (\Throwable $e) {
        }

        // Nếu hủy đơn từ trạng thái khác -> hoàn tồn kho
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            $orderItems = $order->items ?? [];

            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product && isset($product->stock)) {
                    $product->increment('stock', (int) $item->quantity);
                }
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
