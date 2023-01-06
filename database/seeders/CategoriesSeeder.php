<?php
/**
 * CategoriesSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-05 19:42:42
 * @modified   2022-09-05 19:42:42
 */

namespace Database\Seeders;

use Beike\Models\Category;
use Beike\Models\CategoryPath;
use Illuminate\Database\Seeder;
use Beike\Models\CategoryDescription;
use Beike\Admin\Services\CategoryService;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = $this->getCategories();
        if ($categories) {
            Category::query()->truncate();
            foreach ($categories as $item) {
                Category::query()->create($item);
            }
        }

        $descriptions = $this->getCategoryDescriptions();
        if ($descriptions) {
            CategoryDescription::query()->truncate();
            foreach ($descriptions as $item) {
                CategoryDescription::query()->create($item);
            }
        }

        $paths = $this->getCategoryPaths();
        if ($paths) {
            CategoryPath::query()->truncate();
            CategoryPath::query()->insert(collect($paths)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            })->toArray());
        }
        // (new CategoryService())->repairCategories(0);
    }


    public function getCategories()
    {
        return [
            ["id" => 100002, "parent_id" => 100007, "position" => 0, "active" => 1],
            ["id" => 100003, "parent_id" => 0, "position" => 1, "active" => 1],
            ["id" => 100004, "parent_id" => 100007, "position" => 0, "active" => 1],
            ["id" => 100005, "parent_id" => 0, "position" => 0, "active" => 1],
            ["id" => 100006, "parent_id" => 0, "position" => 0, "active" => 1],
            ["id" => 100007, "parent_id" => 0, "position" => 9, "active" => 1],
            ["id" => 100008, "parent_id" => 100006, "position" => 0, "active" => 1],
            ["id" => 100010, "parent_id" => 0, "position" => 0, "active" => 1],
            ["id" => 100011, "parent_id" => 100010, "position" => 0, "active" => 1],
            ["id" => 100012, "parent_id" => 0, "position" => 0, "active" => 1],
            ["id" => 100013, "parent_id" => 100010, "position" => 0, "active" => 1],
            ["id" => 100014, "parent_id" => 100010, "position" => 0, "active" => 1],
            ["id" => 100015, "parent_id" => 100013, "position" => 0, "active" => 1],
            ["id" => 100016, "parent_id" => 100014, "position" => 0, "active" => 1],
            ["id" => 100017, "parent_id" => 100018, "position" => 0, "active" => 1],
            ["id" => 100018, "parent_id" => 0, "position" => 0, "active" => 1]
        ];
    }

    public function getCategoryDescriptions()
    {
        return [
            [
                "id" => 1,
                "category_id" => 100002,
                "locale" => "zh_cn",
                "name" => "平板耳机",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 2,
                "category_id" => 100002,
                "locale" => "en",
                "name" => "Pad",
                "content" => "Pad",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 3,
                "category_id" => 100003,
                "locale" => "zh_cn",
                "name" => "时尚潮流",
                "content" => "时尚潮流",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 4,
                "category_id" => 100003,
                "locale" => "en",
                "name" => "Fashion",
                "content" => "Fashion",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 5,
                "category_id" => 100004,
                "locale" => "zh_cn",
                "name" => "相机",
                "content" => "相机",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 6,
                "category_id" => 100004,
                "locale" => "en",
                "name" => "Camera",
                "content" => "Camera",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 7,
                "category_id" => 100005,
                "locale" => "zh_cn",
                "name" => "特价购买",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 8,
                "category_id" => 100005,
                "locale" => "en",
                "name" => "Special",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 9,
                "category_id" => 100006,
                "locale" => "zh_cn",
                "name" => "运动户外",
                "content" => "运动户外",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 10,
                "category_id" => 100006,
                "locale" => "en",
                "name" => "Sports",
                "content" => "Sports",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 11,
                "category_id" => 100007,
                "locale" => "zh_cn",
                "name" => "电子数码",
                "content" => "电子数码",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 12,
                "category_id" => 100007,
                "locale" => "en",
                "name" => "Electron",
                "content" => "Electron",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 13,
                "category_id" => 100008,
                "locale" => "zh_cn",
                "name" => "帐篷",
                "content" => "帐篷",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 14,
                "category_id" => 100008,
                "locale" => "en",
                "name" => "Tent",
                "content" => "Tent",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 15,
                "category_id" => 100010,
                "locale" => "zh_cn",
                "name" => "男装女装",
                "content" => "男装女装",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 16,
                "category_id" => 100010,
                "locale" => "en",
                "name" => "Clothes",
                "content" => "Fashion",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 17,
                "category_id" => 100011,
                "locale" => "zh_cn",
                "name" => "男装",
                "content" => "男装",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 18,
                "category_id" => 100011,
                "locale" => "en",
                "name" => "Men",
                "content" => "Men",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 19,
                "category_id" => 100012,
                "locale" => "zh_cn",
                "name" => "夏季促销",
                "content" => "夏季促销",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 20,
                "category_id" => 100012,
                "locale" => "en",
                "name" => "Summer Promotion",
                "content" => "Summer promotion",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 21,
                "category_id" => 100013,
                "locale" => "zh_cn",
                "name" => "上装",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 22,
                "category_id" => 100013,
                "locale" => "en",
                "name" => "Top",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 23,
                "category_id" => 100014,
                "locale" => "zh_cn",
                "name" => "下装",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 24,
                "category_id" => 100014,
                "locale" => "en",
                "name" => "Bottom",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 25,
                "category_id" => 100015,
                "locale" => "zh_cn",
                "name" => "棉衣",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 26,
                "category_id" => 100015,
                "locale" => "en",
                "name" => "Cotton",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 27,
                "category_id" => 100016,
                "locale" => "zh_cn",
                "name" => "棉毛裤",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 28,
                "category_id" => 100016,
                "locale" => "en",
                "name" => "Cotton Pants",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 29,
                "category_id" => 100017,
                "locale" => "zh_cn",
                "name" => "智能冰箱",
                "content" => "智能冰箱",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 30,
                "category_id" => 100017,
                "locale" => "en",
                "name" => "IceBox",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 31,
                "category_id" => 100018,
                "locale" => "zh_cn",
                "name" => "家用电器",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 32,
                "category_id" => 100018,
                "locale" => "en",
                "name" => "Electric",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ]
        ];
    }


    public function getCategoryPaths()
    {
        return [
            ["id" => 1, "category_id" => 100003, "path_id" => 100003, "level" => 0],
            ["id" => 2, "category_id" => 100005, "path_id" => 100005, "level" => 0],
            ["id" => 3, "category_id" => 100006, "path_id" => 100006, "level" => 0],
            ["id" => 4, "category_id" => 100008, "path_id" => 100006, "level" => 0],
            ["id" => 5, "category_id" => 100008, "path_id" => 100008, "level" => 1],
            ["id" => 6, "category_id" => 100007, "path_id" => 100007, "level" => 0],
            ["id" => 7, "category_id" => 100002, "path_id" => 100007, "level" => 0],
            ["id" => 8, "category_id" => 100002, "path_id" => 100002, "level" => 1],
            ["id" => 9, "category_id" => 100004, "path_id" => 100007, "level" => 0],
            ["id" => 10, "category_id" => 100004, "path_id" => 100004, "level" => 1],
            ["id" => 11, "category_id" => 100010, "path_id" => 100010, "level" => 0],
            ["id" => 12, "category_id" => 100011, "path_id" => 100010, "level" => 0],
            ["id" => 13, "category_id" => 100011, "path_id" => 100011, "level" => 1],
            ["id" => 14, "category_id" => 100013, "path_id" => 100010, "level" => 0],
            ["id" => 15, "category_id" => 100013, "path_id" => 100013, "level" => 1],
            ["id" => 16, "category_id" => 100015, "path_id" => 100010, "level" => 0],
            ["id" => 17, "category_id" => 100015, "path_id" => 100013, "level" => 1],
            ["id" => 18, "category_id" => 100015, "path_id" => 100015, "level" => 2],
            ["id" => 19, "category_id" => 100014, "path_id" => 100010, "level" => 0],
            ["id" => 20, "category_id" => 100014, "path_id" => 100014, "level" => 1],
            ["id" => 21, "category_id" => 100016, "path_id" => 100010, "level" => 0],
            ["id" => 22, "category_id" => 100016, "path_id" => 100014, "level" => 1],
            ["id" => 23, "category_id" => 100016, "path_id" => 100016, "level" => 2],
            ["id" => 24, "category_id" => 100012, "path_id" => 100012, "level" => 0],
            ["id" => 25, "category_id" => 100018, "path_id" => 100018, "level" => 0],
            ["id" => 26, "category_id" => 100017, "path_id" => 100018, "level" => 0],
            ["id" => 27, "category_id" => 100017, "path_id" => 100017, "level" => 1]
        ];
    }
}
