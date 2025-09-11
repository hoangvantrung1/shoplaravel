<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name'=>'Giày Nam', 'slug'=>'giay-nam']);
        Category::create(['name'=>'Giày Nữ', 'slug'=>'giay-nu']);
        Category::create(['name'=>'Dépa & Sandal', 'slug'=>'depa-sandal']);
    }
}
