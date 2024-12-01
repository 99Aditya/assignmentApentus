<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inputs = [
            [
                'name' => 'Morning',
                'created_at' => date('Y-m-d H:i:s')
            ],[
                'name' => 'Evening',
                'created_at' => date('Y-m-d H:i:s')
            ],[
                'name' => 'Night',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        Category::insert($inputs);
    }
}
