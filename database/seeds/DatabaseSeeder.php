<?php

use App\category;
use App\product;
use App\transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        product::truncate();
        category::truncate();
        transaction::truncate();
        DB::table('category_product')->truncate();

        //num of data

        $userQuantity = 100;
        $categoryQuantity = 30;
        $productQuantity = 1000;
        $transactionQuantity = 1000;

        //factory

        factory(User::class,$userQuantity)->create();
        factory(category::class,$categoryQuantity)->create();

        factory(product::class,$productQuantity)->create()->each(
            function ($product){
                $categories = category::all()->random(mt_rand(1,5))->pluck('id');
                $product->categories()->attach($categories);
            });
        factory(transaction::class,$transactionQuantity)->create();

        // $this->call(UsersTableSeeder::class);
    }
}
