<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::with('user')->latest()->paginate(10);
        return view('admin.addresses.index', compact('addresses'));
    }

    public function show(Address $address)
    {
        $address->load('user');
        return view('admin.addresses.show', compact('address'));
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('admin.addresses.index')
            ->with('success', 'Địa chỉ đã được xóa thành công.');
    }
}