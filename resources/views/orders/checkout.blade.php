@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">Thanh toán</h1>
@if($cart)
<form action="{{ route('order.place') }}" method="POST">
@csrf
<table class="w-full bg-white rounded shadow mb-4">
<tr class="border-b"><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th></tr>
@foreach($cart as $item)
<tr class="border-b">
<td>{{ $item['name'] }}</td>
<td>{{ number_format($item['price']) }} đ</td>
<td>{{ $item['quantity'] }}</td>
</tr>
@endforeach
</table>
<label>Phương thức thanh toán</label>
<select name="payment_method" class="border p-2 rounded mb-2">
<option value="cod">Thanh toán khi nhận hàng</option>
<option value="bank">Chuyển khoản</option>
</select>
<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Đặt hàng</button>
</form>
@else
<p>Giỏ hàng trống</p>
@endif
@endsection
