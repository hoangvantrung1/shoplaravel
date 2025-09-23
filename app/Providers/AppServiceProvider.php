<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use View;

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
        // Chia sẻ biến categories và brands với tất cả các view
        view()->composer('*', function ($view) {
            $categories = Category::all();
            $brands = Brand::all();
            $view->with(compact('categories', 'brands'));
        });
    }
}
