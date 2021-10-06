<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name'=>'php', 'status'=>1]);
        Category::create(['name'=>'c#', 'status'=>1]);
        Category::create(['name'=>'python', 'status'=>1]);
        Category::create(['name'=>'javascript', 'status'=>1]);

    }
}
