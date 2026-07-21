<?php
/**
 * BrandsSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-09-05 19:42:42
 * @modified   2022-09-05 19:42:42
 */

namespace Database\Seeders;

use Beike\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = $this->getItems();

        if ($items) {
            Brand::query()->truncate();
            foreach ($items as $item) {
                Brand::query()->create($item);
            }
        }
    }


    public function getItems()
    {
        return [
            [
                "id" => 1,
                "name" => "Gucci",
                "first" => "G",
                "logo" => "image/catalog/demo/brands/1.webp",
                "sort_order" => 1,
                "active" => 1,
            ],
            [
                "id" => 2,
                "name" => "Valentino",
                "first" => "V",
                "logo" => "image/catalog/demo/brands/10.webp",
                "sort_order" => 2,
                "active" => 1,
            ],
            [
                "id" => 3,
                "name" => "Balenciaga",
                "first" => "B",
                "logo" => "image/catalog/demo/brands/11.webp",
                "sort_order" => 3,
                "active" => 1,
            ],
            [
                "id" => 4,
                "name" => "Saint Laurent",
                "first" => "S",
                "logo" => "image/catalog/demo/brands/12.webp",
                "sort_order" => 4,
                "active" => 1,
            ],
            [
                "id" => 5,
                "name" => "Louis Vuitton",
                "first" => "L",
                "logo" => "image/catalog/demo/brands/2.webp",
                "sort_order" => 5,
                "active" => 1,
            ],
            [
                "id" => 6,
                "name" => "Prada",
                "first" => "P",
                "logo" => "image/catalog/demo/brands/3.webp",
                "sort_order" => 6,
                "active" => 1,
            ],
            [
                "id" => 7,
                "name" => "Chanel",
                "first" => "C",
                "logo" => "image/catalog/demo/brands/4.webp",
                "sort_order" => 7,
                "active" => 1,
            ],
            [
                "id" => 8,
                "name" => "Dior",
                "first" => "D",
                "logo" => "image/catalog/demo/brands/5.webp",
                "sort_order" => 8,
                "active" => 1,
            ],
            [
                "id" => 9,
                "name" => "Armani",
                "first" => "A",
                "logo" => "image/catalog/demo/brands/7.webp",
                "sort_order" => 9,
                "active" => 1,
            ],
            [
                "id" => 10,
                "name" => "Burberry",
                "first" => "B",
                "logo" => "image/catalog/demo/brands/9.webp",
                "sort_order" => 10,
                "active" => 1,
            ],
            [
                "id" => 11,
                "name" => "Versace",
                "first" => "V",
                "logo" => "image/catalog/demo/brands/8.webp",
                "sort_order" => 11,
                "active" => 1,
            ],
            [
                "id" => 12,
                "name" => "Hermès",
                "first" => "H",
                "logo" => "image/catalog/demo/brands/6.webp",
                "sort_order" => 12,
                "active" => 1,
            ]
        ];
    }

}
