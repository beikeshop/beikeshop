<?php
/**
 * CustomerGroupsSeeder.php
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
use Beike\Models\CustomerGroup;
use Beike\Models\CustomerGroupDescription;
use Illuminate\Database\Seeder;

class CustomerGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = $this->getCustomerGroups();
        if ($items) {
            CustomerGroup::query()->truncate();
            foreach ($items as $item) {
                CustomerGroup::query()->create($item);
            }
        }

        $items = $this->getCustomerGroupDescriptions();
        if ($items) {
            CustomerGroupDescription::query()->truncate();
            foreach ($items as $item) {
                CustomerGroupDescription::query()->create($item);
            }
        }
    }


    public function getCustomerGroups(): array
    {
        return [
            [
                "id" => 1,
                "total" => 100,
                "reward_point_factor" => 2,
                "use_point_factor" => 2,
                "discount_factor" => 2,
                "level" => 1,
            ],
            [
                "id" => 2,
                "total" => 200,
                "reward_point_factor" => 2,
                "use_point_factor" => 2,
                "discount_factor" => 2,
                "level" => 2,
            ],
        ];
    }

    public function getCustomerGroupDescriptions(): array
    {
        return [
            [
                "id" => 1,
                "customer_group_id" => 1,
                "locale" => "zh_cn",
                "name" => "白银",
                "description" => "白银组",
            ],
            [
                "id" => 2,
                "customer_group_id" => 1,
                "locale" => "en",
                "name" => "Silver",
                "description" => "Silver Group",
            ],
            [
                "id" => 3,
                "customer_group_id" => 2,
                "locale" => "zh_cn",
                "name" => "黄金",
                "description" => "黄金组",
            ],
            [
                "id" => 4,
                "customer_group_id" => 2,
                "locale" => "en",
                "name" => "Golden",
                "description" => "Golden Group",
            ],
        ];
    }
}
