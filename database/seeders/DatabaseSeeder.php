<?php

namespace Database\Seeders;

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
       // Eloquent::unguard();

        $installerPath = base_path('beike/Installer');
        $path = $installerPath . '/database.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Database seeded!');
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
