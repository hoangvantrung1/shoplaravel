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

        // Loại bỏ các từ intent khi tìm kiếm sản phẩm
        $excludeWords = ['mô', 'tả', 'sản', 'phẩm', 'giá', 'còn', 'hàng', 'tồn', 'kho', 'hết', 'chi', 'tiết', 'bao', 'nhiêu', 'của', 'là', 'gì', 'thế', 'nào', 'cho', 'tôi', 'biết', 'về', 'hỗ', 'trợ', 'tìm', 'kiếm'];
        $productTokens = [];
        if ($normalized !== '') {
            $tokens = array_values(array_filter(explode(' ', $normalized)));
            foreach ($tokens as $t) {
                // Chỉ thêm token nếu:
                // 1. Độ dài > 1
                // 2. Không phải từ intent
                // 3. Không phải số đơn thuần (trừ khi có chữ kèm theo)
                if (strlen($t) > 1 && !in_array($t, $excludeWords) && !preg_match('/^\d+$/', $t)) {
                    $productTokens[] = $t;
                }
            }
        }
        
        // Debug: Nếu message chỉ chứa intent words, đảm bảo productTokens rỗng
        // Ví dụ: "giá sản phẩm" -> productTokens = []

        // Tìm kiếm sản phẩm với nhiều tiêu chí: name, description, slug, brand name, category name
        // CHỈ tìm kiếm khi có productTokens (có từ khóa sản phẩm cụ thể)
        // Nếu chỉ có intent words (như "giá sản phẩm"), KHÔNG tìm kiếm
        $product = null;
        $matchedProducts = collect(); // Lưu tất cả sản phẩm khớp để đánh giá
        
        if (!empty($productTokens)) {
            // Tìm kiếm với OR logic (tìm sản phẩm khớp với bất kỳ token nào)
            $productQuery = Product::with(['brand', 'category'])
                ->where(function ($q) use ($productTokens) {
                    // Tìm trong name, slug, description
                    foreach ($productTokens as $t) {
                        $q->orWhere('name', 'like', '%' . $t . '%')
                          ->orWhere('slug', 'like', '%' . $t . '%')
                          ->orWhere('description', 'like', '%' . $t . '%');
                    }
                })
                // Tìm theo brand name
                ->orWhere(function ($q) use ($productTokens) {
                    $q->whereHas('brand', function ($brandQ) use ($productTokens) {
                        $brandQ->where(function ($bq) use ($productTokens) {
                            foreach ($productTokens as $t) {
                                $bq->orWhere('name', 'like', '%' . $t . '%')
                                  ->orWhere('slug', 'like', '%' . $t . '%');
                            }
                        });
                    });
                })
                // Tìm theo category name
                ->orWhere(function ($q) use ($productTokens) {
                    $q->whereHas('category', function ($catQ) use ($productTokens) {
                        $catQ->where(function ($cq) use ($productTokens) {
                            foreach ($productTokens as $t) {
                                $cq->orWhere('name', 'like', '%' . $t . '%')
                                  ->orWhere('slug', 'like', '%' . $t . '%');
                            }
                        });
                    });
                });

            // Tính điểm khớp cho mỗi sản phẩm
            $allProducts = $productQuery->get();
            $scoredProducts = [];
            
            foreach ($allProducts as $p) {
                $score = 0;
                $productNameLower = mb_strtolower($p->name, 'UTF-8');
                $productSlugLower = mb_strtolower($p->slug ?? '', 'UTF-8');
                $productDescLower = mb_strtolower(strip_tags($p->description ?? ''), 'UTF-8');
                $brandNameLower = mb_strtolower($p->brand->name ?? '', 'UTF-8');
                $categoryNameLower = mb_strtolower($p->category->name ?? '', 'UTF-8');
                
                foreach ($productTokens as $t) {
                    $tLower = mb_strtolower($t, 'UTF-8');
                    
                    // Điểm cao nhất: khớp chính xác tên sản phẩm (10 điểm)
                    if ($productNameLower === $tLower || str_contains($productNameLower, $tLower)) {
                        $score += 10;
                        // Nếu khớp ở đầu tên sản phẩm, thêm điểm
                        if (str_starts_with($productNameLower, $tLower)) {
                            $score += 5;
                        }
                    }
                    
                    // Khớp slug (8 điểm)
                    if ($productSlugLower && str_contains($productSlugLower, $tLower)) {
                        $score += 8;
                    }
                    
                    // Khớp brand name (7 điểm)
                    if ($brandNameLower && str_contains($brandNameLower, $tLower)) {
                        $score += 7;
                    }
                    
                    // Khớp category name (5 điểm)
                    if ($categoryNameLower && str_contains($categoryNameLower, $tLower)) {
                        $score += 5;
                    }
                    
                    // Khớp description (3 điểm)
                    if ($productDescLower && str_contains($productDescLower, $tLower)) {
                        $score += 3;
                    }
                }
                
                // Bonus: nếu khớp tất cả tokens (AND logic)
                $allTokensMatch = true;
                foreach ($productTokens as $t) {
                    $tLower = mb_strtolower($t, 'UTF-8');
                    if (!str_contains($productNameLower, $tLower) && 
                        !str_contains($productSlugLower, $tLower) &&
                        !($brandNameLower && str_contains($brandNameLower, $tLower))) {
                        $allTokensMatch = false;
                        break;
                    }
                }
                if ($allTokensMatch && count($productTokens) > 1) {
                    $score += 15; // Bonus lớn cho khớp tất cả tokens
                }
                
                if ($score > 0) {
                    $scoredProducts[] = ['product' => $p, 'score' => $score];
                }
            }
            
            // Sắp xếp theo điểm giảm dần
            usort($scoredProducts, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            
            // Lấy sản phẩm có điểm cao nhất làm kết quả chính
            // CHỈ tự động chọn sản phẩm nếu:
            // 1. Có ít nhất 1 sản phẩm khớp
            // 2. Điểm số của sản phẩm đầu tiên cao hơn đáng kể (ít nhất 5 điểm) so với sản phẩm thứ 2
            // 3. Hoặc chỉ có 1 sản phẩm khớp
            if (!empty($scoredProducts)) {
                $topScore = $scoredProducts[0]['score'];
                $secondScore = isset($scoredProducts[1]) ? $scoredProducts[1]['score'] : 0;
                
                // Chỉ tự động chọn nếu điểm số rõ ràng cao hơn hoặc chỉ có 1 kết quả
                if (count($scoredProducts) === 1 || ($topScore - $secondScore) >= 5 || $topScore >= 20) {
                    $product = $scoredProducts[0]['product'];
                }
                
                // Lưu tất cả sản phẩm khớp (top 10) để làm gợi ý
                $matchedProducts = collect(array_slice($scoredProducts, 0, 10))->pluck('product');
            }
        }

        // Nếu vẫn chưa có product, thử tìm theo category/brand (ưu tiên brand trước)
        $category = null;
        $brand = null;
        if (!$product && !empty($productTokens)) {
            // Ưu tiên tìm theo brand trước (vì brand thường cụ thể hơn)
            $brandSearchTerms = $productTokens;
            foreach ($brandSearchTerms as $term) {
                // Tìm brand: name và slug (case insensitive)
                $brand = Brand::where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($term) . '%'])
                      ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . strtolower($term) . '%']);
                })->first();
                if ($brand) {
                    // Nếu tìm thấy brand, lấy sản phẩm đầu tiên của brand đó
                    $product = $brand->products()->first();
                    break;
                }
            }
            
            // Nếu vẫn chưa tìm thấy brand, thử tìm sản phẩm trực tiếp với term (fallback)
            if (!$product && !$brand) {
                foreach ($brandSearchTerms as $term) {
                    if (strlen($term) > 2) {
                        $product = Product::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($term) . '%'])->first();
                        if ($product) {
                            // Nếu tìm thấy sản phẩm, thử tìm brand của nó
                            if ($product->brand_id) {
                                $brand = Brand::find($product->brand_id);
                            }
                            break;
                        }
                    }
                }
            }

            // Nếu chưa tìm thấy, thử tìm theo category
            if (!$product && !$brand) {
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
                    : Category::where(function ($q) use ($productTokens) {
                        foreach ($productTokens as $term) {
                            $q->where('name', 'like', '%' . $term . '%')
                              ->orWhere('slug', 'like', '%' . $term . '%');
                        }
                    })->first();
            }
        }

        // Nếu tìm thấy brand nhưng chưa có product cụ thể
        // KHÔNG tự động lấy sản phẩm đầu tiên - luôn hỏi lại người dùng để chọn sản phẩm cụ thể
        // (Trừ khi có lastProduct và người dùng đang tiếp tục câu hỏi về sản phẩm đó)

        // Nếu người dùng chỉ hỏi theo thương hiệu hoặc danh mục, ưu tiên trả về danh sách gợi ý
        if (($category || $brand) && !$product) {
            $suggestions = [];
            if ($category) {
                $candidates = $category->products()->with(['brand', 'category'])->latest('id')->limit(6)->get();
                $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
                $reply = 'Mình tìm thấy một số sản phẩm trong danh mục ' . $category->name . '.';
                if ($askPrice || $askStock || $askDesc) {
                    $reply .= ' Bạn muốn biết thông tin về sản phẩm nào? Hãy chọn một sản phẩm bên dưới:';
                } else {
                    $reply .= ' Bạn có thể chọn một sản phẩm để xem chi tiết:';
                }
                return [
                    'reply' => $reply,
                    'product' => null,
                    'suggestions' => $suggestions,
                ];
            }
            if ($brand) {
                $candidates = $brand->products()->with(['brand', 'category'])->latest('id')->limit(6)->get();
                $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
                $reply = 'Mình tìm thấy một số sản phẩm của thương hiệu ' . $brand->name . '.';
                if ($askPrice || $askStock || $askDesc) {
                    $reply .= ' Bạn muốn biết thông tin về sản phẩm nào? Hãy chọn một sản phẩm bên dưới:';
                } else {
                    $reply .= ' Bạn có thể chọn một sản phẩm để xem chi tiết:';
                }
                return [
                    'reply' => $reply,
                    'product' => null,
                    'suggestions' => $suggestions,
                ];
            }
        }

        // KHÔNG dùng lastProduct khi người dùng chỉ hỏi intent thuần (như "giá sản phẩm")
        // Chỉ dùng lastProduct khi người dùng đang tiếp tục câu hỏi về sản phẩm đó
        // (ví dụ: đã hỏi về iPhone 15, sau đó hỏi "giá bao nhiêu" hoặc "còn hàng không")
        
        // Kiểm tra xem message có chứa từ khóa sản phẩm/brand mới không
        $hasNewProductKeyword = !empty($productTokens);
        
        // KHÔNG tự động dùng lastProduct - luôn hỏi lại người dùng khi câu hỏi chung chung
        // Chỉ dùng lastProduct trong trường hợp đặc biệt: người dùng đang tiếp tục câu hỏi
        // (ví dụ: "giá bao nhiêu" sau khi đã hỏi về một sản phẩm cụ thể)
        // Nhưng để an toàn, chúng ta sẽ KHÔNG dùng lastProduct khi chỉ có intent thuần

        // Nếu tìm được sản phẩm (và sản phẩm này rõ ràng, không mơ hồ)
        if ($product) {
            // Lưu context sản phẩm mới cho phiên làm việc (ghi đè lastProduct cũ)
            session(['last_product_id' => $product->id]);
            
            // Chuẩn bị danh sách gợi ý từ các sản phẩm khớp (loại bỏ sản phẩm chính)
            $suggestions = [];
            if ($matchedProducts->count() > 1) {
                $suggestions = $matchedProducts
                    ->reject(fn ($p) => $p->id === $product->id)
                    ->take(5)
                    ->map(fn ($p) => $this->mapProduct($p))
                    ->values()
                    ->all();
            }
            
            if ($askPrice) {
                $price = $product->sale_price ?: $product->price;
                $reply = "Giá của {$product->name} là " . number_format($price, 0, ',', '.') . "₫.";
                if (!empty($suggestions)) {
                    $reply .= " Mình cũng tìm thấy một số sản phẩm tương tự bên dưới.";
                }
                return [
                    'reply' => $reply,
                    'product' => $this->mapProduct($product),
                    'suggestions' => $suggestions,
                ];
            }

            if ($askStock) {
                $reply = $product->stock > 0
                    ? "{$product->name} hiện còn {$product->stock} sản phẩm trong kho."
                    : "{$product->name} hiện đã hết hàng.";
                if (!empty($suggestions)) {
                    $reply .= " Mình cũng tìm thấy một số sản phẩm tương tự bên dưới.";
                }
                return [
                    'reply' => $reply,
                    'product' => $this->mapProduct($product),
                    'suggestions' => $suggestions,
                ];
            }

            if ($askDesc) {
                $desc = strip_tags((string) $product->description);
                $reply = "Mô tả sản phẩm {$product->name}: " . ($desc ?: 'Sản phẩm này hiện chưa có mô tả chi tiết.');
                if (!empty($suggestions)) {
                    $reply .= " Mình cũng tìm thấy một số sản phẩm tương tự bên dưới.";
                }
                return [
                    'reply' => $reply,
                    'product' => $this->mapProduct($product),
                    'suggestions' => $suggestions,
                ];
            }

            if (str_contains($message, 'thương hiệu') || str_contains($message, 'hãng')) {
                $reply = $product->brand
                    ? "{$product->name} thuộc thương hiệu {$product->brand->name}."
                    : "{$product->name} hiện chưa có thông tin thương hiệu.";
                if (!empty($suggestions)) {
                    $reply .= " Mình cũng tìm thấy một số sản phẩm tương tự bên dưới.";
                }
                return [
                    'reply' => $reply,
                    'product' => $this->mapProduct($product),
                    'suggestions' => $suggestions,
                ];
            }

            $reply = "Bạn muốn biết thêm gì về {$product->name}? (giá, còn hàng, mô tả, thương hiệu...)";
            if (!empty($suggestions)) {
                $reply .= " Mình cũng tìm thấy một số sản phẩm tương tự bên dưới.";
            }
            return [
                'reply' => $reply,
                'product' => $this->mapProduct($product),
                'suggestions' => $suggestions,
            ];
        }

        // Không tìm thấy sản phẩm cụ thể: gợi ý sản phẩm theo category/brand hoặc từ matchedProducts
        $suggestions = [];
        
        // Nếu có matchedProducts (nhiều sản phẩm khớp nhưng không rõ ràng)
        if ($matchedProducts->isNotEmpty() && !$product) {
            $suggestions = $matchedProducts
                ->take(6)
                ->map(fn ($p) => $this->mapProduct($p))
                ->values()
                ->all();
        } elseif ($category) {
            $candidates = $category->products()->with(['brand', 'category'])->latest('id')->limit(6)->get();
            $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
        } elseif ($brand) {
            $candidates = $brand->products()->with(['brand', 'category'])->latest('id')->limit(6)->get();
            $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
        } elseif (!empty($productTokens)) {
            // Tìm kiếm với tất cả tokens
            $searchQuery = Product::with(['brand', 'category']);
            $searchQuery->where(function ($q) use ($productTokens) {
                foreach ($productTokens as $t) {
                    $q->orWhere('name', 'like', '%' . $t . '%')
                      ->orWhere('slug', 'like', '%' . $t . '%')
                      ->orWhere('description', 'like', '%' . $t . '%');
                }
            });
            $candidates = $searchQuery->latest('id')->limit(6)->get();
            $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
        } elseif (!empty($normalized)) {
            $firstToken = explode(' ', $normalized)[0] ?? '';
            if ($firstToken !== '' && strlen($firstToken) > 2) {
                $candidates = Product::with(['brand', 'category'])
                    ->where(function ($q) use ($firstToken) {
                        $q->where('name', 'like', '%' . $firstToken . '%')
                          ->orWhere('slug', 'like', '%' . $firstToken . '%');
                    })
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
        
        // Nếu vẫn không có gợi ý, lấy sản phẩm hot hoặc mới nhất
        if (empty($suggestions)) {
            $candidates = Product::with(['brand', 'category'])
                ->where(function ($q) {
                    $q->where('is_hot', true)
                      ->orWhereNull('is_hot')
                      ->orWhere('is_hot', false);
                })
                ->latest('id')
                ->limit(6)
                ->get();
            $suggestions = $candidates->map(fn ($p) => $this->mapProduct($p))->values()->all();
        }

        // Nếu có intent nhưng không tìm thấy sản phẩm cụ thể
        if ($askPrice) {
            $reply = 'Bạn đang quan tâm đến sản phẩm nào? Mình có thể giúp bạn tìm giá.';
            if (!empty($suggestions)) {
                $reply .= ' Hãy chọn một sản phẩm bên dưới nếu phù hợp:';
            } else {
                $reply .= ' Bạn có thể gõ tên sản phẩm hoặc thương hiệu để mình tìm giúp nhé.';
            }
            return [
                'reply' => $reply,
                'product' => null,
                'suggestions' => $suggestions,
            ];
        }
        if ($askStock) {
            $reply = 'Bạn muốn kiểm tra tồn kho của sản phẩm nào?';
            if (!empty($suggestions)) {
                $reply .= ' Hãy chọn một sản phẩm bên dưới nếu phù hợp:';
            } else {
                $reply .= ' Bạn có thể gõ tên sản phẩm hoặc thương hiệu để mình tìm giúp nhé.';
            }
            return [
                'reply' => $reply,
                'product' => null,
                'suggestions' => $suggestions,
            ];
        }
        if ($askDesc) {
            $reply = 'Bạn muốn xem mô tả của sản phẩm nào?';
            if (!empty($suggestions)) {
                $reply .= ' Hãy chọn một sản phẩm bên dưới nếu phù hợp:';
            } else {
                $reply .= ' Bạn có thể gõ tên sản phẩm hoặc thương hiệu để mình tìm giúp nhé.';
            }
            return [
                'reply' => $reply,
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
        $candidates = Product::with(['brand', 'category'])
            ->latest('id')
            ->limit(300)
            ->get();

        $scored = [];
        foreach ($candidates as $p) {
            $name = mb_strtolower((string) $p->name, 'UTF-8');
            $nameNoAccent = $this->removeAccents($name);
            $slugNoAccent = $this->removeAccents(mb_strtolower($p->slug ?? '', 'UTF-8'));
            $brandNameNoAccent = $this->removeAccents(mb_strtolower($p->brand->name ?? '', 'UTF-8'));

            // Điểm theo số token xuất hiện
            $tokenScore = 0;
            foreach ($tokens as $t) {
                if ($t === '' || strlen($t) < 2) continue;
                $tNoAccent = $this->removeAccents($t);
                
                // Khớp chính xác trong tên (điểm cao)
                if (str_contains($name, mb_strtolower($t, 'UTF-8'))) {
                    $tokenScore += 5;
                }
                // Khớp không dấu trong tên
                elseif ($tNoAccent !== '' && str_contains($nameNoAccent, $tNoAccent)) {
                    $tokenScore += 4;
                }
                // Khớp trong slug
                if ($slugNoAccent && str_contains($slugNoAccent, $tNoAccent)) {
                    $tokenScore += 3;
                }
                // Khớp trong brand name
                if ($brandNameNoAccent && str_contains($brandNameNoAccent, $tNoAccent)) {
                    $tokenScore += 2;
                }
            }

            // Điểm fuzzy: lấy min khoảng cách Levenshtein giữa name và từng token
            $levScore = 0;
            foreach ($tokens as $t) {
                if ($t === '' || strlen($t) < 2) continue;
                $tNoAccent = $this->removeAccents($t);
                $len = max(mb_strlen($nameNoAccent, 'UTF-8'), mb_strlen($tNoAccent, 'UTF-8'));
                if ($len === 0) continue;
                
                // Tính khoảng cách Levenshtein
                $dist = levenshtein($nameNoAccent, $tNoAccent);
                // Chuyển khoảng cách thành điểm (0..1), gần thì cao
                // Chỉ tính điểm nếu khoảng cách nhỏ hơn 30% độ dài
                if ($dist < $len * 0.3) {
                    $sim = max(0, 1 - ($dist / max(1, $len)));
                    $levScore = max($levScore, $sim * 2); // Nhân 2 để tăng trọng số
                }
            }

            $score = $tokenScore + $levScore;
            if ($score > 0) {
                $scored[] = ['score' => $score, 'product' => $p];
            }
        }

        // Sắp xếp theo điểm giảm dần và trả về top N
        usort($scored, function ($a, $b) {
            return $b['score'] <=> $a['score'];
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
