<?php
/**
 * CategoriesSeeder.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-09-05 19:42:42
 * @modified   2022-09-05 19:42:42
 */

namespace Database\Seeders;

use Beike\Models\Brand;
use Beike\Models\Category;
use Beike\Models\CategoryDescription;
use Beike\Models\CategoryPath;
use Illuminate\Database\Seeder;

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
            foreach ($paths as $item) {
                CategoryPath::query()->create($item);
            }
        }

    }


    public function getCategories()
    {
        return [
            [
                "id" => 100000,
                "parent_id" => 0,
                "position" => 2,
                "active" => 1,
            ],
            [
                "id" => 100001,
                "parent_id" => 100000,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100002,
                "parent_id" => 100001,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100003,
                "parent_id" => 0,
                "position" => 1,
                "active" => 1,
            ],
            [
                "id" => 100004,
                "parent_id" => 100008,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100005,
                "parent_id" => 100004,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100006,
                "parent_id" => 100005,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100007,
                "parent_id" => 0,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100008,
                "parent_id" => 0,
                "position" => 9,
                "active" => 1,
            ],
            [
                "id" => 100009,
                "parent_id" => 100007,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100010,
                "parent_id" => 0,
                "position" => 0,
                "active" => 0,
            ],
            [
                "id" => 100011,
                "parent_id" => 0,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100012,
                "parent_id" => 100011,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100013,
                "parent_id" => 0,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100014,
                "parent_id" => 100012,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100015,
                "parent_id" => 100014,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100018,
                "parent_id" => 100012,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100019,
                "parent_id" => 100014,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100020,
                "parent_id" => 100018,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100031,
                "parent_id" => 100000,
                "position" => 0,
                "active" => 1,
            ],
            [
                "id" => 100035,
                "parent_id" => 100011,
                "position" => 0,
                "active" => 1,
            ]
        ];
    }

    public function getCategoryDescriptions()
    {
        return [
            [
                "id" => 11,
                "category_id" => 100005,
                "locale" => "zh_cn",
                "name" => "2-1-1",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 12,
                "category_id" => 100005,
                "locale" => "en",
                "name" => "2-1-1",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 13,
                "category_id" => 100006,
                "locale" => "zh_cn",
                "name" => "特价购买",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 16,
                "category_id" => 100002,
                "locale" => "zh_cn",
                "name" => "平板耳机",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 17,
                "category_id" => 100002,
                "locale" => "en",
                "name" => "平板耳机",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 32,
                "category_id" => 100010,
                "locale" => "zh_cn",
                "name" => "日用品",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 33,
                "category_id" => 100010,
                "locale" => "en",
                "name" => "English日用品",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 54,
                "category_id" => 100015,
                "locale" => "zh_cn",
                "name" => "化妆品3",
                "content" => "化妆品3",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 55,
                "category_id" => 100015,
                "locale" => "en",
                "name" => "化妆品3",
                "content" => "化妆品3",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 60,
                "category_id" => 100013,
                "locale" => "zh_cn",
                "name" => "夏季促销",
                "content" => "夏季促销",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 61,
                "category_id" => 100013,
                "locale" => "en",
                "name" => "Summer promotion",
                "content" => "Summer promotion",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 72,
                "category_id" => 100012,
                "locale" => "zh_cn",
                "name" => "男装",
                "content" => "男装",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 73,
                "category_id" => 100012,
                "locale" => "en",
                "name" => "cotta",
                "content" => "cotta",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 78,
                "category_id" => 100014,
                "locale" => "zh_cn",
                "name" => "上装",
                "content" => "化妆品2",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 79,
                "category_id" => 100014,
                "locale" => "en",
                "name" => "make up",
                "content" => "化妆品2",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 82,
                "category_id" => 100018,
                "locale" => "zh_cn",
                "name" => "下装",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 83,
                "category_id" => 100018,
                "locale" => "en",
                "name" => "makeup and costume",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 84,
                "category_id" => 100019,
                "locale" => "zh_cn",
                "name" => "棉衣",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 85,
                "category_id" => 100019,
                "locale" => "en",
                "name" => "cotton-padded clothes",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 86,
                "category_id" => 100020,
                "locale" => "zh_cn",
                "name" => "棉毛裤",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 87,
                "category_id" => 100020,
                "locale" => "en",
                "name" => "cotton interlock trousers",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 120,
                "category_id" => 100011,
                "locale" => "zh_cn",
                "name" => "男装|女装",
                "content" => "男装|女装",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 121,
                "category_id" => 100011,
                "locale" => "en",
                "name" => "Fashion men's wear1",
                "content" => "Fashion men's wear",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 130,
                "category_id" => 100035,
                "locale" => "zh_cn",
                "name" => "智能冰箱",
                "content" => "智能冰箱",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 131,
                "category_id" => 100035,
                "locale" => "en",
                "name" => "智能冰箱",
                "content" => "智能冰箱",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 150,
                "category_id" => 100007,
                "locale" => "zh_cn",
                "name" => "运动户外",
                "content" => "运动户外",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 151,
                "category_id" => 100007,
                "locale" => "en",
                "name" => "Sports",
                "content" => "Sports",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 152,
                "category_id" => 100008,
                "locale" => "zh_cn",
                "name" => "电子数码",
                "content" => "电子数码",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 153,
                "category_id" => 100008,
                "locale" => "en",
                "name" => "Electron",
                "content" => "Electron",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 154,
                "category_id" => 100009,
                "locale" => "zh_cn",
                "name" => "帐篷",
                "content" => "帐篷",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 155,
                "category_id" => 100009,
                "locale" => "en",
                "name" => "Tent",
                "content" => "Tent",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 156,
                "category_id" => 100004,
                "locale" => "zh_cn",
                "name" => "相机",
                "content" => "相机",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 157,
                "category_id" => 100004,
                "locale" => "en",
                "name" => "Camera",
                "content" => "Camera",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 158,
                "category_id" => 100000,
                "locale" => "zh_cn",
                "name" => "夏季新品",
                "content" => "夏季新品",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 159,
                "category_id" => 100000,
                "locale" => "en",
                "name" => "Summer",
                "content" => "Summer",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 160,
                "category_id" => 100003,
                "locale" => "zh_cn",
                "name" => "时尚潮流",
                "content" => "时尚潮流",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 161,
                "category_id" => 100003,
                "locale" => "en",
                "name" => "Fashion",
                "content" => "Fashion",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 162,
                "category_id" => 100036,
                "locale" => "zh_cn",
                "name" => "冬季套装",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ],
            [
                "id" => 163,
                "category_id" => 100036,
                "locale" => "en",
                "name" => "winter",
                "content" => "",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keyword" => "",
            ]
        ];
    }


    public function getCategoryPaths()
    {
        return [
            [
                "id" => 38,
                "category_id" => 100010,
                "path_id" => 100010,
                "level" => 0,
            ],
            [
                "id" => 72,
                "category_id" => 100013,
                "path_id" => 100013,
                "level" => 0,
            ],
            [
                "id" => 181,
                "category_id" => 100011,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 182,
                "category_id" => 100012,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 183,
                "category_id" => 100012,
                "path_id" => 100012,
                "level" => 1,
            ],
            [
                "id" => 184,
                "category_id" => 100014,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 185,
                "category_id" => 100014,
                "path_id" => 100012,
                "level" => 1,
            ],
            [
                "id" => 186,
                "category_id" => 100014,
                "path_id" => 100014,
                "level" => 2,
            ],
            [
                "id" => 187,
                "category_id" => 100015,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 188,
                "category_id" => 100015,
                "path_id" => 100012,
                "level" => 1,
            ],
            [
                "id" => 189,
                "category_id" => 100015,
                "path_id" => 100014,
                "level" => 2,
            ],
            [
                "id" => 190,
                "category_id" => 100015,
                "path_id" => 100015,
                "level" => 3,
            ],
            [
                "id" => 191,
                "category_id" => 100018,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 192,
                "category_id" => 100018,
                "path_id" => 100012,
                "level" => 1,
            ],
            [
                "id" => 193,
                "category_id" => 100018,
                "path_id" => 100018,
                "level" => 2,
            ],
            [
                "id" => 194,
                "category_id" => 100019,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 195,
                "category_id" => 100019,
                "path_id" => 100012,
                "level" => 1,
            ],
            [
                "id" => 196,
                "category_id" => 100019,
                "path_id" => 100014,
                "level" => 2,
            ],
            [
                "id" => 197,
                "category_id" => 100019,
                "path_id" => 100019,
                "level" => 3,
            ],
            [
                "id" => 198,
                "category_id" => 100020,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 199,
                "category_id" => 100020,
                "path_id" => 100012,
                "level" => 1,
            ],
            [
                "id" => 200,
                "category_id" => 100020,
                "path_id" => 100018,
                "level" => 2,
            ],
            [
                "id" => 201,
                "category_id" => 100020,
                "path_id" => 100020,
                "level" => 3,
            ],
            [
                "id" => 206,
                "category_id" => 100035,
                "path_id" => 100011,
                "level" => 0,
            ],
            [
                "id" => 207,
                "category_id" => 100035,
                "path_id" => 100035,
                "level" => 1,
            ],
            [
                "id" => 264,
                "category_id" => 100007,
                "path_id" => 100007,
                "level" => 0,
            ],
            [
                "id" => 267,
                "category_id" => 100008,
                "path_id" => 100008,
                "level" => 0,
            ],
            [
                "id" => 268,
                "category_id" => 100009,
                "path_id" => 100007,
                "level" => 0,
            ],
            [
                "id" => 269,
                "category_id" => 100009,
                "path_id" => 100009,
                "level" => 1,
            ],
            [
                "id" => 270,
                "category_id" => 100004,
                "path_id" => 100008,
                "level" => 0,
            ],
            [
                "id" => 271,
                "category_id" => 100004,
                "path_id" => 100004,
                "level" => 1,
            ],
            [
                "id" => 272,
                "category_id" => 100005,
                "path_id" => 100008,
                "level" => 0,
            ],
            [
                "id" => 273,
                "category_id" => 100005,
                "path_id" => 100004,
                "level" => 1,
            ],
            [
                "id" => 274,
                "category_id" => 100005,
                "path_id" => 100005,
                "level" => 2,
            ],
            [
                "id" => 275,
                "category_id" => 100000,
                "path_id" => 100000,
                "level" => 0,
            ],
            [
                "id" => 276,
                "category_id" => 100000,
                "path_id" => 100000,
                "level" => 1,
            ],
            [
                "id" => 277,
                "category_id" => 100002,
                "path_id" => 100000,
                "level" => 0,
            ],
            [
                "id" => 278,
                "category_id" => 100002,
                "path_id" => 100000,
                "level" => 1,
            ],
            [
                "id" => 279,
                "category_id" => 100002,
                "path_id" => 100001,
                "level" => 2,
            ],
            [
                "id" => 280,
                "category_id" => 100002,
                "path_id" => 100002,
                "level" => 3,
            ],
            [
                "id" => 281,
                "category_id" => 100003,
                "path_id" => 100000,
                "level" => 0,
            ],
            [
                "id" => 282,
                "category_id" => 100003,
                "path_id" => 100000,
                "level" => 1,
            ],
            [
                "id" => 283,
                "category_id" => 100003,
                "path_id" => 100001,
                "level" => 2,
            ],
            [
                "id" => 284,
                "category_id" => 100003,
                "path_id" => 100002,
                "level" => 3,
            ],
            [
                "id" => 285,
                "category_id" => 100003,
                "path_id" => 100003,
                "level" => 4,
            ],
            [
                "id" => 286,
                "category_id" => 100036,
                "path_id" => 100036,
                "level" => 0,
            ]
        ];
    }
}
