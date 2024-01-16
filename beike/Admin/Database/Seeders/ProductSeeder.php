<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        $date  = Carbon::create(2021);

        for ($i = 0; $i < 1000; $i++) {
            $time      = $date->addMinute();
            $productId = DB::table('products')->insertGetId([
                'image'      => $faker->imageUrl(100, 100),
                'video'      => '',
                'position'   => 0,
                'active'     => 1,
                'variables'  => null,
                'created_at' => $time,
                'updated_at' => $time,
            ]);

            $descriptions = [];
            foreach (locales() as $locale) {
                $descriptions[] = [
                    'product_id'       => $productId,
                    'locale'           => $locale['code'],
                    'name'             => $faker->words(5, true),
                    'content'          => $faker->paragraphs(6, true),
                    'meta_title'       => $faker->words(10, true),
                    'meta_description' => $faker->sentence(10),
                    'meta_keywords'    => $faker->words(10, true),
                    'created_at'       => $time,
                    'updated_at'       => $time,
                ];
            }
            DB::table('product_descriptions')->insert($descriptions);

            $costPrice = $faker->numberBetween(100, 500);
            DB::table('product_skus')->insert([
                'product_id'   => $productId,
                'variants'     => null,
                'position'     => 0,
                'image'        => $faker->imageUrl(100, 100),
                'model'        => $faker->isbn10(),
                'sku'          => $faker->isbn10(),
                'price'        => $costPrice + 10,
                'origin_price' => $costPrice + 50,
                'cost_price'   => $costPrice,
                'quantity'     => $faker->numberBetween(100, 1000),
                'is_default'   => 1,
                'created_at'   => $time,
                'updated_at'   => $time,
            ]);
        }
    }
}
