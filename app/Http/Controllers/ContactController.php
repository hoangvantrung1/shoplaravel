<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'privacy' => 'required|accepted',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'subject.required' => 'Vui lòng chọn chủ đề',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn',
            'privacy.required' => 'Vui lòng đồng ý với chính sách bảo mật',
            'privacy.accepted' => 'Vui lòng đồng ý với chính sách bảo mật',
        ]);

        // Honeypot check
        if (!empty($request->company)) {
            return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ!');
        }

        if ($validator->fails()) {
            return redirect()->route('contact')
                ->withErrors($validator)
                ->withInput();
        }

        // Create contact
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(), 
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}