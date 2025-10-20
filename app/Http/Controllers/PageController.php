<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Hiển thị trang blog
     */
    public function blog()
    {
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('pages.blog', compact('posts'));
    }

    /**
     * Hiển thị chi tiết bài viết
     */
    public function blogDetail($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Tăng view count
        $post->increment('view_count');

        return view('pages.blog-detail', compact('post'));
    }

    /**
     * Hiển thị trang liên hệ
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Xử lý form liên hệ
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        try {
            // Lưu vào database (tạo model Contact nếu cần)
            // Contact::create($request->all());

            // Hoặc chỉ log thông tin
            \Log::info('Contact form data:', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message
            ]);

            return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');

        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra! Vui lòng thử lại.');
        }
    }
}