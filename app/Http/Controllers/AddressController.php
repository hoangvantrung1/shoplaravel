<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->get();
        return view('client.profile.addresses.index', compact('addresses'));
    }

    public function create()
    {
        $address = new Address();
        return view('client.profile.addresses.create', compact('address'));
    }

    // Tách rules để dùng chung cho store và update
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address_line' => 'required|string|max:500',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'is_default' => 'sometimes|boolean',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['user_id'] = auth()->id();
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create($data);

        return redirect()->route('addresses.index')->with('success', 'Đã thêm địa chỉ.');
    }

    public function edit(Address $address)
    {
        $this->authorizeAddress($address);
        return view('client.profile.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeAddress($address);
        $data = $request->validate($this->rules());
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', 'Đã cập nhật địa chỉ.');
    }

    public function destroy(Address $address)
    {
        $this->authorizeAddress($address);
        $address->delete();
        return back()->with('success', 'Đã xóa địa chỉ.');
    }

    protected function authorizeAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
