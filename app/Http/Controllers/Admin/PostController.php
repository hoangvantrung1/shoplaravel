<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Xử lý upload hình ảnh vào public/images/
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Lưu vào public/images/
            $image->move(public_path('images'), $filename);
            $validated['featured_image'] = 'images/' . $filename;
        }

        // Thêm user_id của người tạo
        $validated['user_id'] = auth()->id();

        // Tự động set published_at nếu status là published
        if ($request->status === 'published') {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean'
        ]);

        // Xử lý xóa hình ảnh (từ public/images/)
        if ($request->has('remove_image') && $post->featured_image) {
            $oldImagePath = public_path($post->featured_image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $validated['featured_image'] = null;
        }

        // Xử lý upload hình ảnh mới vào public/images/
        if ($request->hasFile('featured_image')) {
            // Xóa ảnh cũ nếu có (từ public/images/)
            if ($post->featured_image) {
                $oldImagePath = public_path($post->featured_image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Lưu vào public/images/
            $image->move(public_path('images'), $filename);
            $validated['featured_image'] = 'images/' . $filename;
        }

        // Xử lý published_at
        if ($request->status === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif ($request->status === 'draft') {
            $validated['published_at'] = null;
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    public function destroy(Post $post)
    {
        // Xóa hình ảnh nếu có (từ public/images/)
        if ($post->featured_image) {
            $imagePath = public_path($post->featured_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $post->delete();
        
        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được xóa thành công.');
    }
}