<?php

namespace Database\Seeders;

use Beike\Models\CustomerGroup;
use Beike\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        $this->call([
            BrandsSeeder::class,
            CategoriesSeeder::class,
            CountriesSeeder::class,
            CurrenciesSeeder::class,
            CustomerGroupsSeeder::class,
            LanguagesSeeder::class,
            PagesSeeder::class,
            PageCategoriesSeeder::class,
            PluginsSeeder::class,
            ZonesSeeder::class,
            ProductsSeeder::class,
            AttributesSeeder::class,
            SettingsSeeder::class,
            ThemeSeeder::class,
            RmaReasonsSeeder::class,
        ]);
    }

    /**
     * @throws \Throwable
     */
    private function duplicate(): void
    {
        $baseProduct = Product::with(['productCategories', 'descriptions', 'skus'])->findOrFail(2);
        $newProduct = $baseProduct->replicate();
        $newProduct->saveOrFail();
        $newProductId = $newProduct->id;

        if ($newProductId) {
            $relations = $baseProduct->getRelations();
            foreach ($relations as $name => $relation) {
                dump($name);
                foreach ($relation as $relationRecord) {
                    $newRelationship = $relationRecord->replicate();
                    $newRelationship->product_id = $newProductId;
                    $newRelationship->push();
                }
            }
        }
    }
}
