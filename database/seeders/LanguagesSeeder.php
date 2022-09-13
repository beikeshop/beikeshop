<?php
/**
 * LanguagesSeeder.php
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
use Beike\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
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
            Language::query()->truncate();
            foreach ($items as $item) {
                Language::query()->create($item);
            }
        }
    }


    public function getItems()
    {
        return [
            [
                "id" => 1,
                "name" => "中文",
                "code" => "zh_cn",
                "locale" => "",
                "image" => "catalog/favicon.png",
                "sort_order" => 1,
                "status" => 1,
            ],
            [
                "id" => 2,
                "name" => "English",
                "code" => "en",
                "locale" => "",
                "image" => "catalog/demo/services-icon/3.png",
                "sort_order" => 1,
                "status" => 1,
            ]
        ];
    }
}
