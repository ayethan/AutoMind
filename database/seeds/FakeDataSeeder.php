<?php

use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Customer', 200)->create()->each(function($customer) {
            $customer->cars()->saveMany(factory('App\Car', 2)->make());
        });
        factory('App\Service', 100)->create();
        factory('App\Category', 10)->create()->each(function($category) {
            $category->sub_categories()->saveMany(factory('App\SubCategory', 10)->make());
        });
        factory('App\Product', 1000)->create();
        factory('App\ExpenseType', 50)->create();
        factory('App\Expense', 1000)->create();
        factory('App\Sale', 1000)->create();
        factory('App\User', 5)->create();
        factory('App\Supplier', 100)->create();
    }
}
