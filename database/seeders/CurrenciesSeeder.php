<?php
/**
 * CurrenciesSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-05 19:42:42
 * @modified   2022-09-05 19:42:42
 */

namespace Database\Seeders;

use Beike\Models\Brand;
use Beike\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
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
            Currency::query()->truncate();
            foreach ($items as $item) {
                Currency::query()->create($item);
            }
        }
    }


    public function getItems()
    {
        return [
            [
                "id" => 1,
                "name" => "人民币",
                "code" => "CNY",
                "symbol_left" => "￥",
                "symbol_right" => "",
                "decimal_place" => 2,
                "value" => 7.1121,
                "status" => 1,
            ],
            [
                "id" => 2,
                "name" => "USD",
                "code" => "USD",
                "symbol_left" => "$",
                "symbol_right" => "",
                "decimal_place" => 2,
                "value" => 1,
                "status" => 1,
            ],
            [
                "id" => 3,
                "name" => "欧元",
                "code" => "EUR",
                "symbol_left" => "€",
                "symbol_right" => "",
                "decimal_place" => 2,
                "value" => 0.9790,
                "status" => 1,
            ]
        ];
    }
}
