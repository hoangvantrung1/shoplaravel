<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ChatMessage;
use App\Models\Category;
use App\Models\Brand;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000']
        ]);

        try {
        $sessionId = $request->session()->getId();
            $userMessage = trim($validated['message']);

        // Lưu câu hỏi của người dùng
        ChatMessage::create([
            'session_id' => $sessionId,
            'message' => $userMessage,
            'is_admin_reply' => false,
        ]);

        // Gọi hàm xử lý logic trả lời
            $result = $this->generateReply($userMessage);
            $botReply = $result['reply'];

        // Lưu câu trả lời của bot
        ChatMessage::create([
            'session_id' => $sessionId,
            'message' => $botReply,
            'is_admin_reply' => true,
        ]);

            return response()->json([
                'reply' => $botReply,
                'product' => $result['product'] ?? null,
                'suggestions' => $result['suggestions'] ?? [],
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'reply' => 'Xin lỗi, hệ thống đang bận. Bạn vui lòng thử lại sau.',
                'product' => null,
                'suggestions' => [],
            ], 500);
        }
    }

    private function generateReply($message)
    {
        $message = mb_strtolower($message, 'UTF-8');

        // Chuẩn hóa câu hỏi: bỏ ký tự đặc biệt, giữ chữ và số, khoảng trắng
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
        $normalized = preg_replace('/\s+/', ' ', trim($normalized));
        $normalizedNoAccent = $this->removeAccents($message);

        // Intent
        $askPrice = str_contains($message, 'giá') || str_contains($message, 'bao nhiêu');
        $askStock = str_contains($message, 'còn hàng') || str_contains($message, 'hết hàng');
        $askDesc  = str_contains($message, 'mô tả') || str_contains($message, 'chi tiết');

        // Từ điển từ đồng nghĩa đơn giản cho category/brand
        $synonyms = [
            'điện thoại' => ['điện thoại', 'phone', 'smartphone', 'iphone', 'android'],
            'laptop' => ['laptop', 'notebook', 'macbook'],
            'đồng hồ' => ['đồng hồ', 'watch', 'smartwatch', 'galaxy watch', 'apple watch'],
            // có thể mở rộng tiếp...
        ];

        // Lấy sản phẩm gần nhất từ session nếu có
        $lastProductId = session('last_product_id');
        $lastProduct = $lastProductId ? Product::find($lastProductId) : null;

        // Cố gắng tìm sản phẩm theo nhiều chiến lược từ câu hỏi hiện tại
        $productQuery = Product::query();
        if ($normalized !== '') {
            $tokens = array_values(array_filter(explode(' ', $normalized)));
            $productQuery->where(function ($q) use ($tokens, $message) {
                // ưu tiên khớp tất cả token
                foreach ($tokens as $t) {
                    $q->where('name', 'like', '%' . $t . '%');
                }
            });

            // sắp xếp theo mức độ khớp đơn giản (đếm token xuất hiện)
            $scoreExprParts = [];
            foreach ($tokens as $t) {
                $tEsc = str_replace(['%', '_'], ['\\%', '\\_'], $t);
                $scoreExprParts[] = "(CASE WHEN name LIKE '%$tEsc%' THEN 1 ELSE 0 END)";
            }
            if (!empty($scoreExprParts)) {
                $scoreExpr = implode(' + ', $scoreExprParts);
                $productQuery->select('*')->selectRaw("($scoreExpr) as match_score")->orderByDesc('match_score');
            }
        }
        // fallback: nguyên bản user message
        $product = $productQuery->first();

        // Nếu vẫn chưa có product, thử tìm theo category/brand
        $category = null;
        $brand = null;
        if (!$product && $normalized !== '') {
            // map synonyms to a normalized query for category
            $categoryMatch = null;
            foreach ($synonyms as $catKey => $aliasList) {
                foreach ($aliasList as $alias) {
                    if (str_contains($normalized, $alias)) {
                        $categoryMatch = $catKey;
                        break 2;
                    }
                }
            }

            $category = $categoryMatch
                ? Category::where('name', 'like', '%' . $categoryMatch . '%')->first()
                : Category::where(function ($q) use ($normalized) {
                    $q->where('name', 'like', '%' . $normalized . '%')
                      ->orWhere('slug', 'like', '%' . $normalized . '%');
                })->first();

            $brand = null;
            if (!$category) {
                $brand = Brand::where(function ($q) use ($normalized) {
                    $q->where('name', 'like', '%' . $normalized . '%')
                      ->orWhere('slug', 'like', '%' . $normalized . '%');
                })->first();
            }
        }

        // Nếu người dùng chỉ hỏi theo thương hiệu hoặc danh mục, ưu tiên trả về danh sách gợi ý
        if (($category || $brand) && !$askPrice && !$askStock && !$askDesc) {
            $suggestions = [];
            if ($category) {
                $candidates = $category->products()->latest('id')->limit(6)->get();
                $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
                return [
                    'reply' => 'Mình tìm thấy một số sản phẩm trong danh mục ' . $category->name . ':',
                    'product' => null,
                    'suggestions' => $suggestions,
                ];
            }
            if ($brand) {
                $candidates = $brand->products()->latest('id')->limit(6)->get();
                $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
                return [
                    'reply' => 'Mình tìm thấy một số sản phẩm của thương hiệu ' . $brand->name . ':',
                    'product' => null,
                    'suggestions' => $suggestions,
                ];
            }
        }

        // Nếu không tìm thấy nhưng có intent cụ thể và đã có sản phẩm trước đó -> dùng sản phẩm trước
        if (!$product && ($askPrice || $askStock || $askDesc) && $lastProduct) {
            $product = $lastProduct;
        }

        // Nếu tìm được sản phẩm
        if ($product) {
            // Lưu context sản phẩm cho phiên làm việc
            session(['last_product_id' => $product->id]);
            if ($askPrice) {
                $price = $product->sale_price ?: $product->price;
                return [
                    'reply' => "Giá của {$product->name} là " . number_format($price, 0, ',', '.') . "₫.",
                    'product' => $this->mapProduct($product),
                    'suggestions' => [],
                ];
            }

            if ($askStock) {
                $reply = $product->stock > 0
                    ? "{$product->name} hiện còn {$product->stock} sản phẩm trong kho."
                    : "{$product->name} hiện đã hết hàng.";
                return [
                    'reply' => $reply,
                    'product' => $this->mapProduct($product),
                    'suggestions' => [],
                ];
            }

            if ($askDesc) {
                return [
                    'reply' => "Mô tả sản phẩm {$product->name}: " . strip_tags((string) $product->description),
                    'product' => $this->mapProduct($product),
                    'suggestions' => [],
                ];
            }

            if (str_contains($message, 'thương hiệu') || str_contains($message, 'hãng')) {
                $reply = $product->brand
                    ? "{$product->name} thuộc thương hiệu {$product->brand->name}."
                    : "{$product->name} hiện chưa có thông tin thương hiệu.";
                return [
                    'reply' => $reply,
                    'product' => $this->mapProduct($product),
                    'suggestions' => [],
                ];
            }

            return [
                'reply' => "Bạn muốn biết thêm gì về {$product->name}? (giá, còn hàng, mô tả, thương hiệu...)",
                'product' => $this->mapProduct($product),
                'suggestions' => [],
            ];
        }

        // Không tìm thấy: gợi ý sản phẩm theo category/brand hoặc token đầu tiên
        $suggestions = [];
        if ($category) {
            $candidates = $category->products()->latest('id')->limit(6)->get();
            $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
        } elseif ($brand) {
            $candidates = $brand->products()->latest('id')->limit(6)->get();
            $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
        } elseif (!empty($normalized)) {
            $firstToken = explode(' ', $normalized)[0] ?? '';
            if ($firstToken !== '') {
                $candidates = Product::where('name', 'like', '%' . $firstToken . '%')
                    ->latest('id')
                    ->limit(6)
                    ->get();
                $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
            }

            // Fuzzy fallback nếu vẫn rỗng
            if (empty($suggestions)) {
                $suggestions = $this->fuzzySuggest($normalized, 6);
            }
        }

        if ($askPrice) {
            return [
                'reply' => 'Bạn muốn hỏi giá sản phẩm nào? Hãy chọn một sản phẩm bên dưới nếu phù hợp.',
                'product' => null,
                'suggestions' => $suggestions,
            ];
        }
        if ($askStock) {
            return [
                'reply' => 'Bạn muốn kiểm tra tồn kho của sản phẩm nào? Hãy chọn một sản phẩm bên dưới nếu phù hợp.',
                'product' => null,
                'suggestions' => $suggestions,
            ];
        }
        if ($askDesc) {
            return [
                'reply' => 'Bạn muốn xem mô tả của sản phẩm nào? Hãy chọn một sản phẩm bên dưới nếu phù hợp.',
                'product' => null,
                'suggestions' => $suggestions,
            ];
        }

        // Nếu có gợi ý, trả về thông điệp tích cực thay vì báo không tìm thấy
        if (!empty($suggestions)) {
            return [
                'reply' => 'Mình gợi ý một số sản phẩm liên quan, bạn xem thử bên dưới nhé:',
                'product' => null,
                'suggestions' => $suggestions,
            ];
        }

        return [
            'reply' => 'Mình chưa tìm thấy sản phẩm nào liên quan đến câu hỏi của bạn 😢. Bạn có thể gõ rõ tên sản phẩm hơn không?',
            'product' => null,
            'suggestions' => [],
        ];
    }

    private function mapProduct(Product $product): array
    {
        $price = $product->sale_price ?: $product->price;
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'sale_price' => (float) ($product->sale_price ?: 0),
            'display_price' => number_format($price, 0, ',', '.') . '₫',
            'image' => $product->image,
            'stock' => (int) $product->stock,
            'url' => route('product.show', $product->id),
        ];
    }

    private function fuzzySuggest(string $normalized, int $limit = 6): array
    {
        $tokens = array_values(array_filter(explode(' ', $normalized)));
        if (empty($tokens)) {
            return [];
        }

        // Lấy một tập sản phẩm ứng viên để tính điểm (giới hạn để tránh nặng DB)
        $candidates = Product::select('id', 'name', 'price', 'stock', 'image')
            ->latest('id')
            ->limit(200)
            ->get();

        $scored = [];
        foreach ($candidates as $p) {
            $name = mb_strtolower((string) $p->name, 'UTF-8');
            $nameNoAccent = $this->removeAccents($name);

            // Điểm theo số token xuất hiện
            $tokenScore = 0;
            foreach ($tokens as $t) {
                if ($t === '') continue;
                $tNoAccent = $this->removeAccents($t);
                if (str_contains($name, $t) || ($tNoAccent !== '' && str_contains($nameNoAccent, $tNoAccent))) {
                    $tokenScore += 2; // trọng số cao hơn cho khớp trực tiếp
                }
            }

            // Điểm fuzzy: lấy min khoảng cách Levenshtein giữa name và từng token
            $levScore = 0;
            foreach ($tokens as $t) {
                if ($t === '') continue;
                $tNoAccent = $this->removeAccents($t);
                $len = max(mb_strlen($nameNoAccent, 'UTF-8'), mb_strlen($tNoAccent, 'UTF-8'));
                if ($len === 0) continue;
                // Sử dụng so khớp không dấu để tránh lỗi môi trường với iconv và cải thiện độ bền
                $dist = levenshtein($nameNoAccent, $tNoAccent);
                // chuyển khoảng cách thành điểm (0..1), gần thì cao
                $sim = max(0, 1 - ($dist / max(1, $len)));
                $levScore = max($levScore, $sim);
            }

            $score = $tokenScore + $levScore; // tổng hợp đơn giản
            if ($score > 0) {
                $scored[] = ['score' => $score, 'product' => $p];
            }
        }

        // Sắp xếp theo điểm giảm dần và trả về top N
        usort($scored, function ($a, $b) {
            if ($a['score'] === $b['score']) return 0;
            return ($a['score'] < $b['score']) ? 1 : -1;
        });

        $top = array_slice($scored, 0, $limit);
        return array_map(function ($item) {
            /** @var Product $p */
            $p = $item['product'];
            return $this->mapProduct($p);
        }, $top);
    }

    /**
     * Chuẩn hóa chuỗi tiếng Việt về dạng không dấu, loại bỏ ký tự đặc biệt và gom khoảng trắng.
     */
    private function removeAccents(string $str): string
    {
        $trans = [
            'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
            'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
            'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
            'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
            'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
            'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
            'đ'=>'d',
            'À'=>'A','Á'=>'A','Ạ'=>'A','Ả'=>'A','Ã'=>'A','Â'=>'A','Ầ'=>'A','Ấ'=>'A','Ậ'=>'A','Ẩ'=>'A','Ẫ'=>'A','Ă'=>'A','Ằ'=>'A','Ắ'=>'A','Ặ'=>'A','Ẳ'=>'A','Ẵ'=>'A',
            'È'=>'E','É'=>'E','Ẹ'=>'E','Ẻ'=>'E','Ẽ'=>'E','Ê'=>'E','Ề'=>'E','Ế'=>'E','Ệ'=>'E','Ể'=>'E','Ễ'=>'E',
            'Ì'=>'I','Í'=>'I','Ị'=>'I','Ỉ'=>'I','Ĩ'=>'I',
            'Ò'=>'O','Ó'=>'O','Ọ'=>'O','Ỏ'=>'O','Õ'=>'O','Ô'=>'O','Ồ'=>'O','Ố'=>'O','Ộ'=>'O','Ổ'=>'O','Ỗ'=>'O','Ơ'=>'O','Ờ'=>'O','Ớ'=>'O','Ợ'=>'O','Ở'=>'O','Ỡ'=>'O',
            'Ù'=>'U','Ú'=>'U','Ụ'=>'U','Ủ'=>'U','Ũ'=>'U','Ư'=>'U','Ừ'=>'U','Ứ'=>'U','Ự'=>'U','Ử'=>'U','Ữ'=>'U',
            'Ỳ'=>'Y','Ý'=>'Y','Ỵ'=>'Y','Ỷ'=>'Y','Ỹ'=>'Y',
            'Đ'=>'D'
        ];
        $str = strtr($str, $trans);
        $str = preg_replace('/[^A-Za-z0-9\s]/', ' ', $str);
        return preg_replace('/\s+/', ' ', trim($str));
    }
}
