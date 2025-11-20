<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateProductSalePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-sale-price 
                            {--percent= : Phần trăm giảm giá (ví dụ: 10 = giảm 10%)}
                            {--clear : Xóa tất cả sale_price (set = null)}
                            {--category= : Chỉ áp dụng cho category ID cụ thể}
                            {--min-price= : Chỉ áp dụng cho sản phẩm có giá >= min-price}
                            {--max-price= : Chỉ áp dụng cho sản phẩm có giá <= max-price}
                            {--dry-run : Chạy thử không cập nhật database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật sale_price cho tất cả sản phẩm hoặc theo điều kiện';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $percent = $this->option('percent');
        $clear = $this->option('clear');
        $categoryId = $this->option('category');
        $minPrice = $this->option('min-price');
        $maxPrice = $this->option('max-price');
        $dryRun = $this->option('dry-run');

        // Kiểm tra options
        if (!$percent && !$clear) {
            $this->error('Vui lòng chọn một trong các tùy chọn: --percent hoặc --clear');
            $this->info('Ví dụ: php artisan products:update-sale-price --percent=10');
            $this->info('Hoặc: php artisan products:update-sale-price --clear');
            return Command::FAILURE;
        }

        if ($percent && $clear) {
            $this->error('Không thể dùng --percent và --clear cùng lúc!');
            return Command::FAILURE;
        }

        // Validate percent
        if ($percent !== null) {
            $percent = (float) $percent;
            if ($percent < 0 || $percent > 100) {
                $this->error('Phần trăm giảm giá phải từ 0 đến 100!');
                return Command::FAILURE;
            }
        }

        // Build query
        $query = Product::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
            $this->info("Chỉ áp dụng cho category ID: {$categoryId}");
        }

        if ($minPrice) {
            $query->where('price', '>=', (float) $minPrice);
            $this->info("Chỉ áp dụng cho sản phẩm có giá >= " . number_format($minPrice, 0, ',', '.') . "₫");
        }

        if ($maxPrice) {
            $query->where('price', '<=', (float) $maxPrice);
            $this->info("Chỉ áp dụng cho sản phẩm có giá <= " . number_format($maxPrice, 0, ',', '.') . "₫");
        }

        // Chỉ lấy sản phẩm còn hàng
        $query->where('stock', '>', 0);

        $products = $query->get();
        $totalProducts = $products->count();

        if ($totalProducts === 0) {
            $this->warn('Không tìm thấy sản phẩm nào phù hợp với điều kiện!');
            return Command::SUCCESS;
        }

        $this->info("Tìm thấy {$totalProducts} sản phẩm phù hợp.");

        if ($dryRun) {
            $this->warn('=== CHẠY THỬ (DRY RUN) - Không cập nhật database ===');
        }

        // Xác nhận
        if (!$dryRun && !$this->confirm("Bạn có chắc chắn muốn cập nhật {$totalProducts} sản phẩm?", true)) {
            $this->info('Đã hủy thao tác.');
            return Command::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($products as $product) {
                if ($clear) {
                    // Xóa sale_price
                    if (!$dryRun) {
                        $product->sale_price = null;
                        $product->save();
                    }
                    $this->line("✓ {$product->name}: Xóa sale_price");
                    $updated++;
                } elseif ($percent !== null) {
                    // Tính sale_price từ phần trăm giảm giá
                    $currentPrice = (float) $product->price;
                    $discountAmount = $currentPrice * ($percent / 100);
                    $newSalePrice = $currentPrice - $discountAmount;

                    // Đảm bảo sale_price < price
                    if ($newSalePrice >= $currentPrice) {
                        $this->warn("⚠ {$product->name}: Bỏ qua (sale_price >= price)");
                        $skipped++;
                        continue;
                    }

                    if (!$dryRun) {
                        $product->sale_price = round($newSalePrice, 2);
                        $product->save();
                    }

                    $this->line("✓ {$product->name}: " . 
                        number_format($currentPrice, 0, ',', '.') . "₫ → " . 
                        number_format($newSalePrice, 0, ',', '.') . "₫ (Giảm {$percent}%)");
                    $updated++;
                }
            }

            if (!$dryRun) {
                DB::commit();
                Log::info('Cập nhật sale_price cho sản phẩm', [
                    'total' => $totalProducts,
                    'updated' => $updated,
                    'skipped' => $skipped,
                    'percent' => $percent,
                    'clear' => $clear,
                ]);
            } else {
                DB::rollBack();
            }

            $this->newLine();
            $this->info("=== KẾT QUẢ ===");
            $this->info("Tổng số sản phẩm: {$totalProducts}");
            $this->info("Đã cập nhật: {$updated}");
            if ($skipped > 0) {
                $this->warn("Đã bỏ qua: {$skipped}");
            }

            if ($dryRun) {
                $this->warn('Đây là chạy thử. Để thực sự cập nhật, chạy lại lệnh không có --dry-run');
            } else {
                $this->info('Cập nhật thành công!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Lỗi: ' . $e->getMessage());
            Log::error('Lỗi cập nhật sale_price', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
