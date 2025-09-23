<?php
/**
 * ThemeSeeder.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-03-16 14:26:53
 * @modified   2023-03-16 14:26:53
 */

namespace Database\Seeders;

use Beike\Repositories\SettingRepo;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $menuSetting = $this->getMenuSetting();
        SettingRepo::update('system', 'base', ['menu_setting' => $menuSetting]);

        $homeSetting = $this->getHomeSetting();
        SettingRepo::update('system', 'base', ['design_setting' => $homeSetting]);

        $footerSetting = $this->getFooterSetting();
        SettingRepo::update('system', 'base', ['footer_setting' => $footerSetting]);
    }

    /**
     * 设置头部菜单数据
     *
     * @return array
     * @throws \Exception
     */
    private function getMenuSetting(): array
    {
        return [
            "menus" => [
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "",
                            "zh_cn" => ""
                        ],
                        "bg_color" => "#fd560f",
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "category",
                        "value" => 100006,
                        "text" => [],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Sports",
                        "zh_cn" => "运动潮吧"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => [
                        [
                            "name" => [
                                "en" => "leading the fashion",
                                "zh_cn" => "引领时尚"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => []
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 3,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100008,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 5,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100003,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100010,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100010,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "name" => [
                                "en" => "Special offer",
                                "zh_cn" => "特价优惠"
                            ],
                            "type" => "link",
                            "image" => [
                                "image" => [],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => []
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100008,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 6,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100003,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 7,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 8,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 2,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "name" => [
                                "en" => "Popular products",
                                "zh_cn" => "爆款商品"
                            ],
                            "type" => "image",
                            "image" => [
                                "image" => [
                                    "en" => "catalog/demo/banner/2_en.jpg",
                                    "zh_cn" => "catalog/demo/banner/2.jpg"
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => 1,
                                    "text" => [],
                                    "link" => ""
                                ]
                            ],
                            "children" => []
                        ]
                    ]
                ],
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "NEW",
                            "zh_cn" => "新品"
                        ],
                        "bg_color" => "#7628A2",
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "category",
                        "value" => 100003,
                        "text" => [],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Fashion",
                        "zh_cn" => "时尚潮流"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => [
                        [
                            "name" => [
                                "en" => "global purchase",
                                "zh_cn" => "全球购"
                            ],
                            "type" => "link",
                            "image" => [
                                "image" => [],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => []
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 8,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => "",
                                        "new_window" => true
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100003,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 7,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "page",
                                        "value" => 21,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "name" => [
                                "en" => "Fashion",
                                "zh_cn" => "时尚"
                            ],
                            "type" => "image",
                            "image" => [
                                "image" => [
                                    "en" => "catalog/demo/product/16.jpg",
                                    "zh_cn" => "catalog/demo/product/16.jpg"
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => 4,
                                    "text" => [],
                                    "link" => ""
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "custom",
                                        "value" => "https://www.baidu.com",
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => "baidu "
                                        ],
                                        "link" => "",
                                        "new_window" => true
                                    ]
                                ]
                            ]
                        ],
                        [
                            "name" => [
                                "en" => "recommended",
                                "zh_cn" => "店长推荐"
                            ],
                            "type" => "image",
                            "image" => [
                                "image" => [
                                    "en" => "catalog/demo/product/13.jpg",
                                    "zh_cn" => "catalog/demo/product/13.jpg"
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => 2,
                                    "text" => [],
                                    "link" => ""
                                ]
                            ],
                            "children" => []
                        ]
                    ]
                ],
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "",
                            "zh_cn" => ""
                        ],
                        "bg_color" => "#fd560f",
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "category",
                        "value" => 100007,
                        "text" => [],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Digital",
                        "zh_cn" => "数码产品"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => [
                        [
                            "name" => [
                                "en" => "Big promotion",
                                "zh_cn" => "大牌促销"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => []
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 2,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 8,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 9,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 1,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 6,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "name" => [
                                "en" => "Activity of gift",
                                "zh_cn" => "活动礼品"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => []
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100003,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100006,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100012,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100006,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100010,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ]
                            ]
                        ],
                        [
                            "name" => [
                                "en" => "All three fold",
                                "zh_cn" => "全场三折"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => []
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 1,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 3,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 7,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 8,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 9,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "Hot",
                            "zh_cn" => "热卖"
                        ],
                        "bg_color" => "#FF4D00",
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "category",
                        "value" => 100018,
                        "text" => [],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Hot",
                        "zh_cn" => "热卖特惠"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => []
                ],
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "Sales",
                            "zh_cn" => "大牌直销"
                        ],
                        "bg_color" => "#00C5C5",
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "static",
                        "value" => "brands.index",
                        "text" => [],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Brand",
                        "zh_cn" => "国际潮牌"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => []
                ],
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "",
                            "zh_cn" => ""
                        ],
                        "bg_color" => "",
                        "text_color" => ""
                    ],
                    "link" => [
                        "type" => "page_category",
                        "value" => "1",
                        "text" => [],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "News",
                        "zh_cn" => "公司新闻"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => []
                ]
            ]
        ];
    }

    /**
     * 设置首页装修数据
     *
     * @return mixed
     * @throws \Exception
     */
    private function getHomeSetting(): array
    {
        return [
            "modules" =>[
                [
                    "code" =>"img_text_slideshow",
                    "content" => [
                        "style" => [
                            "background_color" =>""
                        ],
                        'module_size' => 'w-100',
                        "floor" => [
                            "zh_cn" =>"",
                            "en" =>""
                        ],
                        "images" =>[
                            [
                                "image" =>"catalog/demo/banner/text-image-banner-1.jpg",
                                "sub_title" => [
                                    "zh_cn" =>"促销服装专场",
                                    "en" =>"New Arrivals"
                                ],
                                "title" => [
                                    "zh_cn" =>"全场5折限时促销，好礼不断！",
                                    "en" =>"50% off sitewide for a limited time"
                                ],
                                "description" => [
                                    "zh_cn" =>"即刻起购买者享受终身质保",
                                    "en" =>"Immediately buyers enjoy a lifetime warranty"
                                ],
                                "text_position" =>"start",
                                "show" =>false,
                                "link" => [
                                    "type" =>"category",
                                    "value" =>100003,
                                    "link" =>""
                                ]
                            ],
                            [
                                "image" =>"catalog/demo/banner/text-image-banner-2.jpg",
                                "show" =>true,
                                "sub_title" => [
                                    "zh_cn" =>"促销服装专场",
                                    "en" =>"Fashion"
                                ],
                                "title" => [
                                    "zh_cn" =>"时尚，新品上市，首单立减",
                                    "en" =>"Beauty Feast, Fashion Awards"
                                ],
                                "text_position" =>"start",
                                "description" => [
                                    "zh_cn" =>"即刻起购买者享受终身质保",
                                    "en" =>"Immediately buyers enjoy a lifetime warranty"
                                ],
                                "link" => [
                                    "link" =>"",
                                    "type" =>"category",
                                    "value" =>100003
                                ]
                            ]
                        ],
                        "scroll_text" => [
                            "text" => [
                                "zh_cn" =>"时尚盛典，全场低至2折起！",
                                "en" =>"Fashion Gala: Up to 80% Off!"
                            ],
                            "bg" =>"#FFE8E1",
                            "color" =>"#333333",
                            "padding" =>"36",
                            "font_size" =>"20"
                        ]
                    ],
                    "module_id" =>"esccEyoWPfQldq02",
                    "name" =>"图文幻灯片",
                    "view_path" =>""
                ],
                [
                    "code" =>"image403",
                    "content" => [
                        "style" => [
                            "background_color" =>""
                        ],
                        'module_size' => 'container-fluid',
                        "floor" => [
                            "zh_cn" =>"",
                            "en" =>""
                        ],
                        "images" =>[
                            [
                                "image" =>"catalog/demo/banner/banner-403-1.jpg",
                                "sub_title" => [
                                    "zh_cn" =>"新品上市尽享时尚潮流",
                                    "en" =>"New arrivals, trendsetting"
                                ],
                                "title" => [
                                    "zh_cn" =>"限时抢购",
                                    "en" =>"Flash Sale"
                                ],
                                "show" =>false,
                                "link" => [
                                    "type" =>"category",
                                    "value" =>100007,
                                    "link" =>""
                                ]
                            ],
                            [
                                "image" =>"catalog/demo/banner/banner-403-2.jpg",
                                "sub_title" => [
                                    "zh_cn" =>"限时折扣不容错过",
                                    "en" =>"Grab limited offers"
                                ],
                                "title" => [
                                    "zh_cn" =>"全场7折",
                                    "en" =>"30% Off All Items"
                                ],
                                "show" =>false,
                                "link" => [
                                    "type" =>"category",
                                    "value" =>100003,
                                    "link" =>""
                                ]
                            ],
                            [
                                "image" =>"catalog/demo/banner/banner-403-3.jpg",
                                "sub_title" => [
                                    "zh_cn" =>"会员专享优惠来袭",
                                    "en" =>"Exclusive member deals"
                                ],
                                "title" => [
                                    "zh_cn" =>"清仓大促",
                                    "en" =>"Clearance Sale"
                                ],
                                "show" =>false,
                                "link" => [
                                    "type" =>"category",
                                    "value" =>100018,
                                    "link" =>""
                                ]
                            ],
                            [
                                "image" =>"catalog/demo/banner/banner-403-4.jpg",
                                "sub_title" => [
                                    "zh_cn" =>"全场商品优惠大放送",
                                    "en" =>"Amazing sale now"
                                ],
                                "title" => [
                                    "zh_cn" =>"节日盛典",
                                    "en" =>"Weekend Special"
                                ],
                                "show" =>true,
                                "link" => [
                                    "type" =>"category",
                                    "value" =>100006,
                                    "link" =>""
                                ]
                            ]
                        ],
                        "title" => [
                            "zh_cn" =>"换季大减价，优惠不停歇",
                            "en" =>"Trendy new arrivals"
                        ],
                        "sub_title" => [
                            "zh_cn" =>"换季大减价，优惠不停歇！抓住机会，为您的衣橱注入新鲜血液。精选服饰低至3折起，时尚与实惠并存，让您在省钱的同时，也能引领潮流。快来选购，享受这场购物盛宴，错过不再有！",
                            "en" =>"Fresh fashion hits the shelves! Revamp your look with our latest trendy items. Keep your style sharp and on-trend with our stylish picks. Shop now!"
                        ]
                    ],
                    "module_id" =>"g0fXCoqrEiqD4VrI",
                    "name" =>"一行四图-3",
                    "view_path" =>""
                ],
                [
                    "code" =>"tab_product",
                    "content" => [
                        "style" => [
                            "background_color" =>""
                        ],
                        'module_size' => 'container-fluid',
                        "floor" => [
                            "zh_cn" =>"",
                            "en" =>""
                        ],
                        "editableTabsValue" =>"0",
                        "tabs" =>[
                            [
                                "title" => [
                                    "zh_cn" =>"时尚单品",
                                    "en" =>"Fashion sheet"
                                ],
                                "products" =>[
                                    5,
                                    9,
                                    10,
                                    11,
                                    12,
                                    13,
                                    14,
                                    15
                                ]
                            ],
                            [
                                "title" => [
                                    "zh_cn" =>"潮流穿搭",
                                    "en" =>"Trendy outfits"
                                ],
                                "products" =>[
                                    39,
                                    15,
                                    1,
                                    4,
                                    13,
                                    7,
                                    8,
                                    4
                                ]
                            ],
                            [
                                "title" => [
                                    "zh_cn" =>"最新促销",
                                    "en" =>"Promotions"
                                ],
                                "products" =>[
                                    1,
                                    2,
                                    3,
                                    4,
                                    5,
                                    7,
                                    8,
                                    11
                                ]
                            ]
                        ],
                        "title" => [
                            "zh_cn" =>"推荐商品",
                            "en" =>"Fashion items"
                        ]
                    ],
                    "module_id" =>"s6e7e3vucriazzbi",
                    "name" =>"选项卡商品"
                ],
                [
                    "code" =>"img_text_banner",
                    "content" => [
                        "style" => [
                            "background_color" =>""
                        ],
                        'module_size' => 'container-fluid',
                        "floor" => [
                            "zh_cn" =>"",
                            "en" =>""
                        ],
                        "bg_color" =>"#F7F6F1",
                        "image" =>"/catalog/demo/banner/text-image-banner-7.jpg",
                        "title" => [
                            "zh_cn" =>"潮流新品上市，引领时尚新风潮！",
                            "en" =>"Spring Into Style"
                        ],
                        "description" => [
                            "zh_cn" =>"我们精心挑选了最新潮流单品，从街头风到高级定制，每一件都是时尚达人的必备之选。无论是日常穿搭还是特殊场合，这些新品都能让您成为众人焦点。快来选购，让您的衣橱焕发新光彩！",
                            "en" =>"Kick off the new season with our Spring collection! Refresh your wardrobe with vibrant colors and fresh designs. Enjoy exclusive discounts on the latest fashion trends that will have you stepping into spring with confidence and style. Hurry, these deals won't last long!"
                        ],
                        "link" => [
                            "type" =>"category",
                            "value" =>100007,
                            "link" =>""
                        ]
                    ],
                    "module_id" =>"xaUi1BwJXGJBMd1M",
                    "name" =>"图文横幅",
                    "view_path" =>""
                ],
                [
                    "code" =>"brand",
                    "content" => [
                        "style" => [
                            "background_color" =>""
                        ],
                        'module_size' => 'container-fluid',
                        "floor" => [
                            "en" =>"",
                            "zh_cn" =>""
                        ],
                        "full" =>true,
                        "title" => [
                            "en" =>"Recommended Brand",
                            "zh_cn" =>"推荐品牌"
                        ],
                        "brands" =>[
                            1,
                            2,
                            3,
                            4,
                            5,
                            6,
                            7,
                            8,
                            9,
                            10,
                            11,
                            12
                        ]
                    ],
                    "module_id" =>"yln7isoqlxovqz3k",
                    "name" =>"品牌模块"
                ],
                [
                    "code" =>"page",
                    "content" => [
                        "style" => [
                            "background_color" =>""
                        ],
                        'module_size' => 'container-fluid',
                        "floor" => [
                            "zh_cn" =>"",
                            "en" =>""
                        ],
                        "items" =>[
                            22,
                            23,
                            24,
                            25
                        ],
                        "title" => [
                            "zh_cn" =>"新闻博客",
                            "en" =>"News Blog"
                        ]
                    ],
                    "module_id" =>"24P9p4bRwk1nbtXE",
                    "name" =>"文章模块",
                    "view_path" =>""
                ]
            ]
        ];
    }


    /**
     * 设置底部装修数据
     */
    private function getFooterSetting(): array
    {
        return [
            "services" => [
                "enable" => true,
                "items" => [
                    [
                        "image" => "catalog/demo/services-icon/4.png",
                        "title" => [
                            "en" => "Material world",
                            "zh_cn" => "物行天下"
                        ],
                        "sub_title" => [
                            "en" => "Multi - warehouse fast delivery",
                            "zh_cn" => "多仓直发 极速配送多仓直发 极速配送"
                        ],
                        "show" => false
                    ],
                    [
                        "image" => "catalog/demo/services-icon/3.png",
                        "title" => [
                            "en" => "Return all",
                            "zh_cn" => "退换无忧"
                        ],
                        "sub_title" => [
                            "en" => "Rest assured shopping return worry",
                            "zh_cn" => "放心购物 退还无忧放心购物 退还无忧"
                        ],
                        "show" => false
                    ],
                    [
                        "image" => "catalog/demo/services-icon/1.png",
                        "title" => [
                            "en" => "Delicate service",
                            "zh_cn" => "精致服务"
                        ],
                        "sub_title" => [
                            "en" => "Exquisite service and after-sales guarantee",
                            "zh_cn" => "精致服务 售后保障精致服务 售后保障"
                        ],
                        "show" => false
                    ],
                    [
                        "image" => "catalog/demo/services-icon/2.png",
                        "title" => [
                            "en" => "With reduced activity",
                            "zh_cn" => "满减活动"
                        ],
                        "sub_title" => [
                            "en" => "If 500 yuan is exceeded, a reduction of 90 yuan will be given",
                            "zh_cn" => "满500元立减90，新用户立减200"
                        ],
                        "show" => true
                    ]
                ]
            ],
            "content" => [
                "intro" => [
                    "logo" => "catalog/logo.png",
                    "text" => [
                        "en" => "<p>Chengdu Guangda Network Technology Co., Ltd. is a high-tech enterprise mainly engaged in Internet development. The company was established in August 2014.</p>",
                        "zh_cn" => "<p style=\"line-height: 1.4;\"><strong>成都光大网络科技有限公司</strong></p>\n<p style=\"line-height: 1.4;\">是一家专业互联网开发的高科技企业，公司成立于2014年8月。</p>\n<p style=\"line-height: 1.4;\">公司以为客户创造价值为核心价值观，帮助中小企业利用互联网工具提升产品销售。</p>"
                    ],
                    "social_network" =>[
                        [
                            "image" =>"/catalog/demo/social/twitter.png",
                            "link" =>"/",
                            "show" =>false
                        ],
                        [
                            "image" =>"/catalog/demo/social/facebook.png",
                            "link" =>"/",
                            "show" =>false
                        ],
                        [
                            "image" =>"/catalog/demo/social/youtube.png",
                            "link" =>"/",
                            "show" =>false
                        ],
                        [
                            "image" =>"/catalog/demo/social/instagram.png",
                            "link" =>"/",
                            "show" =>false
                        ],
                        [
                            "image" =>"/catalog/demo/social/pinterest.png",
                            "link" =>"/",
                            "show" =>false
                        ]
                    ]
                ],
                "link1" => [
                    "title" => [
                        "en" => "About us",
                        "zh_cn" => "关于我们"
                    ],
                    "links" => [
                        [
                            "link" => "",
                            "type" => "page",
                            "value" => 21,
                            "text" => [
                                "en" => "About us",
                                "zh_cn" => "关于我们"
                            ]
                        ],
                        [
                            "type" => "page",
                            "value" => 18,
                            "text" => [],
                            "link" => ""
                        ],
                        [
                            "type" => "page",
                            "value" => 12,
                            "text" => [],
                            "link" => ""
                        ],
                        [
                            "type" => "static",
                            "value" => "account.order.index",
                            "text" => [
                                "en" => "",
                                "zh_cn" => ""
                            ],
                            "link" => ""
                        ]
                    ]
                ],
                "link2" => [
                    "title" => [
                        "en" => "Account",
                        "zh_cn" => "会员中心"
                    ],
                    "links" => [
                        [
                            "type" => "static",
                            "value" => "account.index",
                            "text" => [],
                            "link" => ""
                        ],
                        [
                            "type" => "static",
                            "value" => "account.order.index",
                            "text" => [],
                            "link" => ""
                        ],
                        [
                            "type" => "static",
                            "value" => "account.wishlist.index",
                            "text" => [],
                            "link" => ""
                        ],
                        [
                            "type" => "static",
                            "value" => "brands.index",
                            "text" => [
                                "en" => "",
                                "zh_cn" => ""
                            ],
                            "link" => ""
                        ]
                    ]
                ],
                "link3" => [
                    "title" => [
                        "en" => "Other",
                        "zh_cn" => "其他"
                    ],
                    "links" => [
                        [
                            "type" => "static",
                            "value" => "brands.index",
                            "text" => [],
                            "link" => ""
                        ],
                        [
                            "type" => "static",
                            "value" => "account.index",
                            "text" => [
                                "en" => "",
                                "zh_cn" => ""
                            ],
                            "link" => ""
                        ],
                        [
                            "type" => "page",
                            "value" => 20,
                            "text" => [
                                "en" => "",
                                "zh_cn" => ""
                            ],
                            "link" => ""
                        ],
                        [
                            "type" => "page",
                            "value" => 21,
                            "text" => [
                                "en" => "",
                                "zh_cn" => ""
                            ],
                            "link" => ""
                        ]
                    ]
                ],
                "contact" => [
                    "telephone" => "028-xxxxxxxx",
                    "address" => [
                        "en" => "Your company address",
                        "zh_cn" => "您的公司地址"
                    ],
                    "email" => "support@example.com"
                ]
            ],
            "bottom" => [
                "copyright" => [
                    "en" => "<div>Chengdu Guangda Network Technology &copy; ".date('Y')."</div>",
                    "zh_cn" => "<div>成都光大网络科技 &copy; ".date('Y')."</div>"
                ],
                "image" => "catalog/demo/banner/pay_icons.png"
            ]
        ];
    }

     /**
     * 设置头部装修数据
     */
    private function getHeaderSetting(): array
    {
        return [
            "header_ads" => [
                "bg_color" =>"#333333",
                "color" =>"#ffffff",
                "active" =>true,
                "items" =>[
                    [
                        "title" => [
                            "zh_cn" =>"春日时尚焕新季 全场新品限时5折起",
                            "en" =>"Spring Fashion Refresh Season: Limited Time 50% Off on New Arrivals"
                        ],
                        "link" => [
                            "type" =>"category",
                            "value" =>100003,
                            "link" =>""
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" =>"春季潮流新品发布 特惠5折起购",
                            "en" =>"Spring Trend Collection Launch: Special 50% Off Offer"
                        ],
                        "link" => [
                            "type" =>"category",
                            "value" =>100010,
                            "link" =>""
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" =>"春意浓情特卖会 全场5折起狂欢",
                            "en" =>"Spring Affection Sale Event: Up to 50% Off on All Items"
                        ],
                        "link" => [
                            "type" =>"category",
                            "value" =>100010,
                            "link" =>""
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" =>"春季新品大放价 5折起享时尚",
                            "en" =>"Spring New Arrivals Mega Sale: Enjoy 50% Off on Fashion"
                        ],
                        "link" => [
                            "type" =>"category",
                            "value" =>100003,
                            "link" =>""
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" =>"春日风尚大促销 精选商品5折起",
                            "en" =>"Spring Style Promotion: Select Items Starting at 50% Off"
                        ],
                        "link" => [
                            "type" =>"category",
                            "value" =>100007,
                            "link" =>""
                        ]
                    ]
                ]
            ]
        ];
    }
}
