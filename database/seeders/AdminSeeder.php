<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Trung',
            'email' => 'trungadmin@gmail.com',
            'password' => Hash::make('123456'),
            'is_admin' => 1
        ]);
    }
}
