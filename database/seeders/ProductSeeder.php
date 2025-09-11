<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'category_id'=>1,
            'name'=>'Giày Thể Thao Nam',
            'slug'=>'giay-the-thao-nam',
            'price'=>1200000,
            'image'=>'/images/product1.jpg',
            'is_hot'=>true
        ]);

        Product::create([
            'category_id'=>2,
            'name'=>'Giày Thể Thao Nữ',
            'slug'=>'giay-the-thao-nu',
            'price'=>1100000,
            'image'=>'/images/product2.jpg',
            'is_hot'=>true
        ]);

        Product::create([
            'category_id'=>3,
            'name'=>'Dépa Sandal',
            'slug'=>'depa-sandal',
            'price'=>500000,
            'image'=>'/images/product3.jpg',
            'is_hot'=>true
        ]);
    }
}
