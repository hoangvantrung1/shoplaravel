<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

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
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'=>'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status'=>$request->status]);

        return redirect()->back()->with('success','Cập nhật trạng thái thành công!');
    }
}
