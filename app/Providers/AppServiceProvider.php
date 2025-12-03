<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Chia sẻ biến categories và brands với các view cần thiết
         * Sử dụng cache để tối ưu hiệu năng, chỉ load khi cần
         * Cache 1 giờ (3600 giây) - có thể điều chỉnh theo nhu cầu
         */
        view()->composer([
            'layouts.*',
            'components.*',
            'products.*',
            'admin.products.*',
            'admin.categories.*',
            'admin.brands.*'
        ], function ($view) {
            // Sử dụng cache để tránh query database mỗi request
            $categories = Cache::remember('categories_all', 3600, function () {
                return Category::all();
            });
            
            $brands = Cache::remember('brands_all', 3600, function () {
                return Brand::all();
            });
            
            $view->with(compact('categories', 'brands'));
        });
    }
}
