<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá mới từ người dùng
     * Hỗ trợ upload ảnh (nhiều) và video (1)
     */
    public function store(Request $request, $productId)
    {
        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:2000',
                'media.*' => 'nullable|file|max:20480', // Tối đa 20MB, kiểm tra chi tiết bên dưới
            ], [
                'rating.required' => 'Vui lòng chọn đánh giá sao.',
                'rating.integer' => 'Đánh giá không hợp lệ.',
                'rating.min' => 'Đánh giá tối thiểu là 1 sao.',
                'rating.max' => 'Đánh giá tối đa là 5 sao.',
                'comment.max' => 'Nhận xét không được vượt quá 2000 ký tự.',
                'media.*.file' => 'File tải lên không hợp lệ.',
                'media.*.max' => 'File tải lên không được vượt quá 20MB.',
            ]);

            $product = Product::findOrFail($productId);

            // Sử dụng transaction để đảm bảo tính nhất quán
            DB::beginTransaction();

            [$images, $videoPath] = $this->handleMediaUploads($request->file('media', []));

            // Tạo review với ảnh/video
            $review = Review::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'rating' => (int)$request->rating,
                'comment' => $request->comment,
                'images' => !empty($images) ? $images : null,
                'video' => $videoPath,
                'is_approved' => true, // Tự động duyệt (có thể thay đổi thành false nếu cần admin duyệt)
            ]);

            // Ghi log
            Log::info('Review created', [
                'review_id' => $review->id,
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'rating' => $review->rating,
                'has_images' => !empty($images),
                'has_video' => !empty($videoPath),
            ]);

            DB::commit();

            return back()->with('success', 'Bạn đã gửi đánh giá thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Xóa file đã upload nếu có lỗi
            $this->deleteMediaFiles($images ?? [], $videoPath ?? null);

            Log::error('Review creation failed', [
                'error' => $e->getMessage(),
                'product_id' => $productId,
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại!');
        }
    }

    /**
     * Cập nhật đánh giá của người dùng
     */
    public function update(Request $request, Review $review)
    {
        abort_if($review->user_id !== auth()->id(), 403);

        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:2000',
                'media.*' => 'nullable|file|max:20480',
                'clear_media' => 'nullable|boolean',
            ], [
                'rating.required' => 'Vui lòng chọn đánh giá sao.',
                'rating.integer' => 'Đánh giá không hợp lệ.',
                'rating.min' => 'Đánh giá tối thiểu là 1 sao.',
                'rating.max' => 'Đánh giá tối đa là 5 sao.',
                'comment.max' => 'Nhận xét không được vượt quá 2000 ký tự.',
                'media.*.file' => 'File tải lên không hợp lệ.',
                'media.*.max' => 'File tải lên không được vượt quá 20MB.',
            ]);

            DB::beginTransaction();

            $images = $review->images ?? [];
            $videoPath = $review->video;

            $uploadedFiles = $request->file('media', []);
            $clearMedia = $request->boolean('clear_media');

            if ($clearMedia) {
                $this->deleteMediaFiles($images, $videoPath);
                $images = [];
                $videoPath = null;
            }

            if (!empty($uploadedFiles)) {
                $this->deleteMediaFiles($images, $videoPath);
                [$images, $videoPath] = $this->handleMediaUploads($uploadedFiles);
            }

            $review->update([
                'rating' => (int) $request->rating,
                'comment' => $request->comment,
                'images' => !empty($images) ? $images : null,
                'video' => $videoPath,
            ]);

            DB::commit();

            return back()->with('success', 'Đánh giá đã được cập nhật.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review update failed', [
                'error' => $e->getMessage(),
                'review_id' => $review->id,
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Không thể cập nhật đánh giá. Vui lòng thử lại.');
        }
    }

    /**
     * Xóa đánh giá của người dùng
     */
    public function destroy(Review $review)
    {
        abort_if($review->user_id !== auth()->id(), 403);

        try {
            DB::beginTransaction();

            $this->deleteMediaFiles($review->images ?? [], $review->video);
            $review->delete();

            DB::commit();

            return back()->with('success', 'Đánh giá đã được xóa.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review delete failed', [
                'error' => $e->getMessage(),
                'review_id' => $review->id,
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Không thể xóa đánh giá. Vui lòng thử lại.');
        }
    }

    /**
     * Xử lý upload media (ảnh & video)
     */
    protected function handleMediaUploads(array $files): array
    {
        $images = [];
        $videoPath = null;
        $imageCount = 0;

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $mimeType = $file->getMimeType();

            if (Str::startsWith($mimeType, 'image/')) {
                if ($imageCount >= 5) {
                    throw ValidationException::withMessages([
                        'media' => 'Bạn chỉ có thể tải lên tối đa 5 hình ảnh.',
                    ]);
                }

                if ($file->getSize() > 5 * 1024 * 1024) {
                    throw ValidationException::withMessages([
                        'media' => 'Mỗi hình ảnh không được vượt quá 5MB.',
                    ]);
                }

                $imageCount++;
                $images[] = $file->store('reviews/images', 'public');
            } elseif (Str::startsWith($mimeType, 'video/')) {
                if ($videoPath) {
                    throw ValidationException::withMessages([
                        'media' => 'Chỉ được tải lên một video cho mỗi đánh giá.',
                    ]);
                }

                if ($file->getSize() > 20 * 1024 * 1024) {
                    throw ValidationException::withMessages([
                        'media' => 'Video không được vượt quá 20MB.',
                    ]);
                }

                $videoPath = $file->store('reviews/videos', 'public');
            } else {
                throw ValidationException::withMessages([
                    'media' => 'Định dạng file không được hỗ trợ.',
                ]);
            }
        }

        return [$images, $videoPath];
    }

    /**
     * Xóa file media khỏi storage
     */
    protected function deleteMediaFiles(?array $images, ?string $videoPath): void
    {
        if (!empty($images)) {
            foreach ($images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        if (!empty($videoPath)) {
            Storage::disk('public')->delete($videoPath);
        }
    }
}



