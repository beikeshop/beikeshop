<?php
/**
 * PluginsSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-09-05 20:42:42
 * @modified   2022-09-05 20:42:42
 */

namespace Database\Seeders;

use Beike\Models\Plugin;
use Illuminate\Database\Seeder;

class PluginsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = $this->getPlugins();

        if ($pages) {
            Plugin::query()->truncate();
            foreach ($pages as $item) {
                Plugin::query()->create($item);
            }
        }
    }


    public function getPlugins()
    {
        return [
            [
                'id' => '39',
                'type' => 'payment',
                'code' => 'bk_stripe',
            ],
            [
                'id' => '42',
                'type' => 'view',
                'code' => 'header_menu',
            ],
            [
                'id' => '44',
                'type' => 'total',
                'code' => 'service_charge',
            ],
            [
                'id' => '52',
                'type' => 'view',
                'code' => 'latest_products',
            ],
            [
                'id' => '55',
                'type' => 'shipping',
                'code' => 'flat_shipping',
            ],
            [
                'id' => '56',
                'type' => 'payment',
                'code' => 'paypal',
            ],
            [
                'id' => '57',
                'type' => 'payment',
                'code' => 'stripe',
            ]
        ];
    }
}
