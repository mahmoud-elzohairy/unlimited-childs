<?php

use Illuminate\Database\Seeder;
use App\Category;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = Category::count();
        if ($count == 0) {

            $cats = [
                ['name' => 'Category A', 'parent_id' => null],
                ['name' => 'Category B', 'parent_id' => null],
                ['name' => 'Category C', 'parent_id' => null],
                ['name' => 'SUB B1', 'parent_id' => 2],
                ['name' => 'SUB B2', 'parent_id' => 2],
                ['name' => 'SUB B3', 'parent_id' => 2],
                ['name' => 'SUB C1', 'parent_id' => 3],
                ['name' => 'SUB C2', 'parent_id' => 3],
                ['name' => 'SUB SUB B2-1', 'parent_id' => 5],
                ['name' => 'SUB SUB B2-2', 'parent_id' => 5],
                ['name' => 'SUB SUB SUB B2-1', 'parent_id' => 9],
                ['name' => 'SUB SUB SUB B2-2', 'parent_id' => 9],
                ['name' => 'SUB SUB SUB B2-3', 'parent_id' => 9],
            ];
            foreach ($cats as $key => $cat) {
                Category::create($cat);
            }

        }

    }
}
