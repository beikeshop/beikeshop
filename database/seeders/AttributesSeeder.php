<?php
/**
 * AttributesSeeder.php
 *  php artisan db:seed --class=AttributesSeeder
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-01-12 19:20:05
 * @modified   2023-01-12 19:20:05
 */

namespace Database\Seeders;

use Beike\Models\Attribute;
use Beike\Models\AttributeDescription;
use Beike\Models\AttributeGroup;
use Beike\Models\AttributeGroupDescription;
use Beike\Models\AttributeValue;
use Beike\Models\AttributeValueDescription;
use Beike\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AttributeGroup::query()->truncate();
        AttributeGroupDescription::query()->truncate();
        Attribute::query()->truncate();
        AttributeDescription::query()->truncate();
        AttributeValue::query()->truncate();
        AttributeValueDescription::query()->truncate();
        ProductAttribute::query()->truncate();

        // 属性组
        $attributeGroupsNumber = 4;
        for ($i = 1; $i <= $attributeGroupsNumber; $i++) {
            AttributeGroup::query()->create([
                'sort_order' => $i
            ]);
        }

        // 属性组描述
        $items = $this->getGroupDescriptions();
        AttributeGroupDescription::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray()
        );

        // 属性
        $items = $this->getAttributes();
        Attribute::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray()
        );

        // 属性描述
        $items = $this->getAttributeDescriptions();
        AttributeDescription::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray()
        );

        // 属性值
        $items = $this->getAttributeValues();
        AttributeValue::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray()
        );

        // 属性值描述
        $items = $this->getAttributeValueDescriptions();
        AttributeValueDescription::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray()
        );

        // 产品属性关联
        $items = $this->productAttributes();
        ProductAttribute::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray()
        );
    }


    private function getGroupDescriptions(): array
    {
        return [
            ["attribute_group_id" => 1, "locale" => "zh_cn", "name" => "默认"],
            ["attribute_group_id" => 1, "locale" => "en", "name" => "Default"],
            ["attribute_group_id" => 2, "locale" => "zh_cn", "name" => "衣服"],
            ["attribute_group_id" => 2, "locale" => "en", "name" => "Clothing"],
            ["attribute_group_id" => 3, "locale" => "zh_cn", "name" => "运动户外"],
            ["attribute_group_id" => 3, "locale" => "en", "name" => "Outdoor sport"],
            ["attribute_group_id" => 4, "locale" => "zh_cn", "name" => "数码"],
            ["attribute_group_id" => 4, "locale" => "en", "name" => "Digital"]
        ];
    }


    private function getAttributes(): array
    {
        return [
            ["attribute_group_id" => 2, "sort_order" => 0],
            ["attribute_group_id" => 2, "sort_order" => 0],
            ["attribute_group_id" => 2, "sort_order" => 0],
            ["attribute_group_id" => 3, "sort_order" => 0],
            ["attribute_group_id" => 4, "sort_order" => 0],
            ["attribute_group_id" => 4, "sort_order" => 0]
        ];
    }


    private function getAttributeDescriptions(): array
    {
        return [
            ["attribute_id" => 1, "locale" => "zh_cn", "name" => "功能"],
            ["attribute_id" => 1, "locale" => "en", "name" => "Features"],
            ["attribute_id" => 2, "locale" => "zh_cn", "name" => "面料"],
            ["attribute_id" => 2, "locale" => "en", "name" => "Fabric"],
            ["attribute_id" => 3, "locale" => "zh_cn", "name" => "样式"],
            ["attribute_id" => 3, "locale" => "en", "name" => "Style"],
            ["attribute_id" => 4, "locale" => "zh_cn", "name" => "缓震"],
            ["attribute_id" => 4, "locale" => "en", "name" => "Cushioning"],
            ["attribute_id" => 5, "locale" => "zh_cn", "name" => "CUP"],
            ["attribute_id" => 5, "locale" => "en", "name" => "CUP"],
            ["attribute_id" => 6, "locale" => "zh_cn", "name" => "内存"],
            ["attribute_id" => 6, "locale" => "en", "name" => "Memory"]
        ];
    }


    private function getAttributeValues(): array
    {
        return [
            ["attribute_id" => 2],
            ["attribute_id" => 2],
            ["attribute_id" => 1],
            ["attribute_id" => 3],
            ["attribute_id" => 2],
            ["attribute_id" => 2],
            ["attribute_id" => 2],
            ["attribute_id" => 3],
            ["attribute_id" => 3],
            ["attribute_id" => 3],
            ["attribute_id" => 1],
            ["attribute_id" => 1],
            ["attribute_id" => 4],
            ["attribute_id" => 4],
            ["attribute_id" => 4],
            ["attribute_id" => 4],
            ["attribute_id" => 4],
            ["attribute_id" => 5],
            ["attribute_id" => 5],
            ["attribute_id" => 5],
            ["attribute_id" => 5],
            ["attribute_id" => 6],
            ["attribute_id" => 6]
        ];
    }


    private function getAttributeValueDescriptions(): array
    {
        return [
            ["attribute_value_id" => 1, "locale" => "zh_cn", "name" => "棉"],
            ["attribute_value_id" => 1, "locale" => "en", "name" => "Cotton"],
            ["attribute_value_id" => 2, "locale" => "zh_cn", "name" => "麻"],
            ["attribute_value_id" => 2, "locale" => "en", "name" => "Numb"],
            ["attribute_value_id" => 5, "locale" => "en", "name" => "Silk"],
            ["attribute_value_id" => 5, "locale" => "zh_cn", "name" => "丝"],
            ["attribute_value_id" => 6, "locale" => "en", "name" => "Hair"],
            ["attribute_value_id" => 6, "locale" => "zh_cn", "name" => "毛"],
            ["attribute_value_id" => 7, "locale" => "zh_cn", "name" => "化纤"],
            ["attribute_value_id" => 7, "locale" => "en", "name" => "Chemical fiber"],
            ["attribute_value_id" => 4, "locale" => "zh_cn", "name" => "圆领"],
            ["attribute_value_id" => 4, "locale" => "en", "name" => "Round neck"],
            ["attribute_value_id" => 8, "locale" => "en", "name" => "Collarless"],
            ["attribute_value_id" => 8, "locale" => "zh_cn", "name" => "无领"],
            ["attribute_value_id" => 9, "locale" => "en", "name" => "Short sleeve"],
            ["attribute_value_id" => 9, "locale" => "zh_cn", "name" => "短袖"],
            ["attribute_value_id" => 10, "locale" => "zh_cn", "name" => "T恤"],
            ["attribute_value_id" => 10, "locale" => "en", "name" => "T-shirt"],
            ["attribute_value_id" => 3, "locale" => "zh_cn", "name" => "防水"],
            ["attribute_value_id" => 3, "locale" => "en", "name" => "Water proof"],
            ["attribute_value_id" => 11, "locale" => "zh_cn", "name" => "保暖"],
            ["attribute_value_id" => 11, "locale" => "en", "name" => "keep warm"],
            ["attribute_value_id" => 12, "locale" => "zh_cn", "name" => "防晒"],
            ["attribute_value_id" => 12, "locale" => "en", "name" => "Sun protection"],
            ["attribute_value_id" => 13, "locale" => "zh_cn", "name" => "Zoom气垫"],
            ["attribute_value_id" => 13, "locale" => "en", "name" => "Zoom Air Cushion"],
            ["attribute_value_id" => 14, "locale" => "zh_cn", "name" => "Max气垫"],
            ["attribute_value_id" => 14, "locale" => "en", "name" => "Max Air Cushion"],
            ["attribute_value_id" => 15, "locale" => "zh_cn", "name" => "Boost缓震材料"],
            ["attribute_value_id" => 15, "locale" => "en", "name" => "Boost cushioning material"],
            ["attribute_value_id" => 16, "locale" => "zh_cn", "name" => "Lightstrike科技"],
            ["attribute_value_id" => 16, "locale" => "en", "name" => "Lightstrike Technology"],
            ["attribute_value_id" => 17, "locale" => "en", "name" => "Fuel Cell Technology"],
            ["attribute_value_id" => 17, "locale" => "zh_cn", "name" => "FuelCell科技"],
            ["attribute_value_id" => 18, "locale" => "zh_cn", "name" => "i3"],
            ["attribute_value_id" => 18, "locale" => "en", "name" => "i3"],
            ["attribute_value_id" => 19, "locale" => "zh_cn", "name" => "i5"],
            ["attribute_value_id" => 19, "locale" => "en", "name" => "i5"],
            ["attribute_value_id" => 20, "locale" => "zh_cn", "name" => "i7"],
            ["attribute_value_id" => 20, "locale" => "en", "name" => "i7"],
            ["attribute_value_id" => 21, "locale" => "zh_cn", "name" => "i9"],
            ["attribute_value_id" => 21, "locale" => "en", "name" => "i9"],
            ["attribute_value_id" => 22, "locale" => "zh_cn", "name" => "DDR3"],
            ["attribute_value_id" => 22, "locale" => "en", "name" => "DDR3"],
            ["attribute_value_id" => 23, "locale" => "zh_cn", "name" => "DDR4"],
            ["attribute_value_id" => 23, "locale" => "en", "name" => "DDR4"],
        ];
    }


    private function productAttributes(): array
    {
        return [
            ["product_id" => 5, "attribute_id" => 1, "attribute_value_id" => 11,],
            ["product_id" => 5, "attribute_id" => 2, "attribute_value_id" => 5,],
            ["product_id" => 5, "attribute_id" => 5, "attribute_value_id" => 20,],
            ["product_id" => 5, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 5, "attribute_id" => 3, "attribute_value_id" => 10,],
            ["product_id" => 6, "attribute_id" => 3, "attribute_value_id" => 8,],
            ["product_id" => 6, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 6, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 6, "attribute_id" => 4, "attribute_value_id" => 14,],
            ["product_id" => 7, "attribute_id" => 1, "attribute_value_id" => 11,],
            ["product_id" => 7, "attribute_id" => 3, "attribute_value_id" => 10,],
            ["product_id" => 7, "attribute_id" => 5, "attribute_value_id" => 21,],
            ["product_id" => 7, "attribute_id" => 2, "attribute_value_id" => 5,],
            ["product_id" => 7, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 8, "attribute_id" => 1, "attribute_value_id" => 12,],
            ["product_id" => 8, "attribute_id" => 4, "attribute_value_id" => 14,],
            ["product_id" => 8, "attribute_id" => 5, "attribute_value_id" => 21,],
            ["product_id" => 8, "attribute_id" => 6, "attribute_value_id" => 22,],
            ["product_id" => 8, "attribute_id" => 3, "attribute_value_id" => 9,],
            ["product_id" => 8, "attribute_id" => 2, "attribute_value_id" => 5,],
            ["product_id" => 9, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 9, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 9, "attribute_id" => 3, "attribute_value_id" => 8,],
            ["product_id" => 9, "attribute_id" => 6, "attribute_value_id" => 22,],
            ["product_id" => 9, "attribute_id" => 4, "attribute_value_id" => 16,],
            ["product_id" => 9, "attribute_id" => 5, "attribute_value_id" => 19,],
            ["product_id" => 10, "attribute_id" => 4, "attribute_value_id" => 17,],
            ["product_id" => 10, "attribute_id" => 2, "attribute_value_id" => 5,],
            ["product_id" => 10, "attribute_id" => 5, "attribute_value_id" => 20,],
            ["product_id" => 10, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 10, "attribute_id" => 3, "attribute_value_id" => 9,],
            ["product_id" => 11, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 11, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 11, "attribute_id" => 3, "attribute_value_id" => 4,],
            ["product_id" => 11, "attribute_id" => 4, "attribute_value_id" => 14,],
            ["product_id" => 11, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 12, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 12, "attribute_id" => 2, "attribute_value_id" => 5,],
            ["product_id" => 12, "attribute_id" => 4, "attribute_value_id" => 13,],
            ["product_id" => 12, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 12, "attribute_id" => 5, "attribute_value_id" => 20,],
            ["product_id" => 12, "attribute_id" => 3, "attribute_value_id" => 9,],
            ["product_id" => 13, "attribute_id" => 1, "attribute_value_id" => 11,],
            ["product_id" => 13, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 13, "attribute_id" => 3, "attribute_value_id" => 9,],
            ["product_id" => 13, "attribute_id" => 4, "attribute_value_id" => 14,],
            ["product_id" => 13, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 13, "attribute_id" => 5, "attribute_value_id" => 19,],
            ["product_id" => 14, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 14, "attribute_id" => 2, "attribute_value_id" => 2,],
            ["product_id" => 14, "attribute_id" => 3, "attribute_value_id" => 8,],
            ["product_id" => 14, "attribute_id" => 4, "attribute_value_id" => 14,],
            ["product_id" => 14, "attribute_id" => 5, "attribute_value_id" => 20,],
            ["product_id" => 14, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 15, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 15, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 15, "attribute_id" => 2, "attribute_value_id" => 6,],
            ["product_id" => 15, "attribute_id" => 5, "attribute_value_id" => 20,],
            ["product_id" => 15, "attribute_id" => 4, "attribute_value_id" => 16,],
            ["product_id" => 15, "attribute_id" => 6, "attribute_value_id" => 22,],
            ["product_id" => 35, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 35, "attribute_id" => 2, "attribute_value_id" => 6,],
            ["product_id" => 35, "attribute_id" => 3, "attribute_value_id" => 8,],
            ["product_id" => 35, "attribute_id" => 4, "attribute_value_id" => 15,],
            ["product_id" => 35, "attribute_id" => 5, "attribute_value_id" => 21,],
            ["product_id" => 35, "attribute_id" => 6, "attribute_value_id" => 22,],
            ["product_id" => 39, "attribute_id" => 1, "attribute_value_id" => 11,],
            ["product_id" => 39, "attribute_id" => 2, "attribute_value_id" => 2,],
            ["product_id" => 39, "attribute_id" => 3, "attribute_value_id" => 9,],
            ["product_id" => 39, "attribute_id" => 4, "attribute_value_id" => 15,],
            ["product_id" => 39, "attribute_id" => 5, "attribute_value_id" => 19,],
            ["product_id" => 39, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 1, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 1, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 1, "attribute_id" => 4, "attribute_value_id" => 15,],
            ["product_id" => 1, "attribute_id" => 3, "attribute_value_id" => 10,],
            ["product_id" => 1, "attribute_id" => 5, "attribute_value_id" => 21,],
            ["product_id" => 1, "attribute_id" => 6, "attribute_value_id" => 22,],
            ["product_id" => 2, "attribute_id" => 1, "attribute_value_id" => 3,],
            ["product_id" => 2, "attribute_id" => 2, "attribute_value_id" => 1,],
            ["product_id" => 2, "attribute_id" => 3, "attribute_value_id" => 4,],
            ["product_id" => 2, "attribute_id" => 4, "attribute_value_id" => 13,],
            ["product_id" => 2, "attribute_id" => 5, "attribute_value_id" => 18,],
            ["product_id" => 2, "attribute_id" => 6, "attribute_value_id" => 22,],
            ["product_id" => 3, "attribute_id" => 1, "attribute_value_id" => 12,],
            ["product_id" => 3, "attribute_id" => 2, "attribute_value_id" => 5,],
            ["product_id" => 3, "attribute_id" => 4, "attribute_value_id" => 14,],
            ["product_id" => 3, "attribute_id" => 5, "attribute_value_id" => 20,],
            ["product_id" => 3, "attribute_id" => 6, "attribute_value_id" => 23,],
            ["product_id" => 4, "attribute_id" => 1, "attribute_value_id" => 11,],
            ["product_id" => 4, "attribute_id" => 2, "attribute_value_id" => 7,],
            ["product_id" => 4, "attribute_id" => 3, "attribute_value_id" => 10,],
            ["product_id" => 4, "attribute_id" => 5, "attribute_value_id" => 21,],
            ["product_id" => 4, "attribute_id" => 6, "attribute_value_id" => 23,]
        ];
    }
}
