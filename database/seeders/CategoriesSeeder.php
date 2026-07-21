<?php
/**
 * CategoriesSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
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
            ["id" => 100002, "parent_id" => 100007, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/1.webp"],
            ["id" => 100003, "parent_id" => 0, "position" => 1, "active" => 1, "image" => "image/catalog/demo/product/2.webp"],
            ["id" => 100004, "parent_id" => 100007, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/3.webp"],
            ["id" => 100005, "parent_id" => 0, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/4.webp"],
            ["id" => 100006, "parent_id" => 0, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/5.webp"],
            ["id" => 100007, "parent_id" => 0, "position" => 9, "active" => 1, "image" => "image/catalog/demo/product/6.webp"],
            ["id" => 100008, "parent_id" => 100006, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/7.webp"],
            ["id" => 100010, "parent_id" => 0, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/17.webp"],
            ["id" => 100011, "parent_id" => 100010, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/9.webp"],
            ["id" => 100012, "parent_id" => 0, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/10.webp"],
            ["id" => 100013, "parent_id" => 100010, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/11.webp"],
            ["id" => 100014, "parent_id" => 100010, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/12.webp"],
            ["id" => 100015, "parent_id" => 100013, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/13.webp"],
            ["id" => 100016, "parent_id" => 100014, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/14.webp"],
            ["id" => 100017, "parent_id" => 100018, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/15.webp"],
            ["id" => 100018, "parent_id" => 0, "position" => 0, "active" => 1, "image" => "image/catalog/demo/product/16.webp"]
        ];
    }

    public function getCategoryDescriptions()
    {
        return [
            [
                "id" => 1,
                "category_id" => 100002,
                "locale" => "zh_cn",
                "name" => "长裤",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 2,
                "category_id" => 100002,
                "locale" => "en",
                "name" => "Trousers",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 3,
                "category_id" => 100003,
                "locale" => "zh_cn",
                "name" => "时尚潮流",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 4,
                "category_id" => 100003,
                "locale" => "en",
                "name" => "Fashion",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 5,
                "category_id" => 100004,
                "locale" => "zh_cn",
                "name" => "衬衫",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 6,
                "category_id" => 100004,
                "locale" => "en",
                "name" => "Shirts",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 7,
                "category_id" => 100005,
                "locale" => "zh_cn",
                "name" => "特价购买",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 8,
                "category_id" => 100005,
                "locale" => "en",
                "name" => "Special",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 9,
                "category_id" => 100006,
                "locale" => "zh_cn",
                "name" => "运动户外",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 10,
                "category_id" => 100006,
                "locale" => "en",
                "name" => "Sports",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 11,
                "category_id" => 100007,
                "locale" => "zh_cn",
                "name" => "冬季特惠",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 12,
                "category_id" => 100007,
                "locale" => "en",
                "name" => "Winter Special Offer",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 13,
                "category_id" => 100008,
                "locale" => "zh_cn",
                "name" => "潮流装扮",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 14,
                "category_id" => 100008,
                "locale" => "en",
                "name" => "Trendy Outfits",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 15,
                "category_id" => 100010,
                "locale" => "zh_cn",
                "name" => "男装女装",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 16,
                "category_id" => 100010,
                "locale" => "en",
                "name" => "Clothes",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 17,
                "category_id" => 100011,
                "locale" => "zh_cn",
                "name" => "男装",
                "content" => '',
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 18,
                "category_id" => 100011,
                "locale" => "en",
                "name" => "Men",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 19,
                "category_id" => 100012,
                "locale" => "zh_cn",
                "name" => "夏季促销",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 20,
                "category_id" => 100012,
                "locale" => "en",
                "name" => "Summer Promotion",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 21,
                "category_id" => 100013,
                "locale" => "zh_cn",
                "name" => "上装",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 22,
                "category_id" => 100013,
                "locale" => "en",
                "name" => "Top",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 23,
                "category_id" => 100014,
                "locale" => "zh_cn",
                "name" => "下装",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 24,
                "category_id" => 100014,
                "locale" => "en",
                "name" => "Bottom",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 25,
                "category_id" => 100015,
                "locale" => "zh_cn",
                "name" => "棉衣",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 26,
                "category_id" => 100015,
                "locale" => "en",
                "name" => "Cotton",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 27,
                "category_id" => 100016,
                "locale" => "zh_cn",
                "name" => "棉毛裤",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 28,
                "category_id" => 100016,
                "locale" => "en",
                "name" => "Cotton Pants",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 29,
                "category_id" => 100017,
                "locale" => "zh_cn",
                "name" => "运动服",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 30,
                "category_id" => 100017,
                "locale" => "en",
                "name" => "Sportswear",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 31,
                "category_id" => 100018,
                "locale" => "zh_cn",
                "name" => "家居服",
                "content" => "在快节奏的都市生活中，时尚不仅是一种穿搭，更是一种态度。我们精心甄选全球潮流单品，从经典简约到前卫设计，让每一位追求个性的你都能找到专属风格。无论是日常通勤、休闲出街，还是特别场合，我们都为你准备了多样化的选择。让时尚融入生活，每一次穿搭都成为自我表达的舞台。",
                "meta_title" => "",
                "meta_description" => "",
                "meta_keywords" => ""
            ],
            [
                "id" => 32,
                "category_id" => 100018,
                "locale" => "en",
                "name" => "Loungewear",
                "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry lorem Ipsum has been the industry's standard dummy text ever sinces the typesetting remaining essentially unchanged.",
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
