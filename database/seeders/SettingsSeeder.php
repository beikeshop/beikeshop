<?php
/**
 * SettingsSeeder.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-09-05 19:42:42
 * @modified   2022-09-05 19:42:42
 */

namespace Database\Seeders;

use Beike\Models\Brand;
use Beike\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
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
            Setting::query()->truncate();
            foreach ($items as $item) {
                Setting::query()->create($item);
            }
        }
    }


    public function getItems()
    {
        return
            [
                [
                    "id" => 94,
                    "type" => "system",
                    "space" => "base",
                    "name" => "country_id",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-06-30 10:54:48",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 142,
                    "type" => "system",
                    "space" => "base",
                    "name" => "locale",
                    "value" => "en",
                    "json" => 0,
                    "created_at" => "2022-07-01 10:53:32",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 222,
                    "type" => "system",
                    "space" => "base",
                    "name" => "design_setting",
                    "value" => $this->getHomeSetting(),
                    "json" => 1,
                    "created_at" => "2022-07-14 06:19:44",
                    "updated_at" => "2022-08-26 09:01:54"
                ],
                [
                    "id" => 236,
                    "type" => "system",
                    "space" => "base",
                    "name" => "theme",
                    "value" => "default",
                    "json" => 0,
                    "created_at" => "2022-07-20 08:41:23",
                    "updated_at" => "2022-08-19 16:23:06"
                ],
                [
                    "id" => 242,
                    "type" => "plugin",
                    "space" => "service_charge",
                    "name" => "status",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-07-25 06:54:25",
                    "updated_at" => "2022-07-25 06:54:25"
                ],
                [
                    "id" => 244,
                    "type" => "system",
                    "space" => "base",
                    "name" => "status",
                    "value" => "",
                    "json" => 0,
                    "created_at" => "2022-07-25 12:35:25",
                    "updated_at" => "2022-07-29 06:49:54"
                ],
                [
                    "id" => 246,
                    "type" => "system",
                    "space" => "base",
                    "name" => "admin_name",
                    "value" => "admin",
                    "json" => 0,
                    "created_at" => "2022-07-25 13:25:12",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 247,
                    "type" => "system",
                    "space" => "base",
                    "name" => "tax",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-07-27 06:11:16",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 248,
                    "type" => "system",
                    "space" => "base",
                    "name" => "tax_address",
                    "value" => "payment",
                    "json" => 0,
                    "created_at" => "2022-07-27 06:22:01",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 249,
                    "type" => "system",
                    "space" => "base",
                    "name" => "currency",
                    "value" => "USD",
                    "json" => 0,
                    "created_at" => "2022-07-28 09:33:58",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 257,
                    "type" => "system",
                    "space" => "base",
                    "name" => "zone_id",
                    "value" => "3",
                    "json" => 0,
                    "created_at" => "2022-07-29 06:48:36",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 258,
                    "type" => "system",
                    "space" => "base",
                    "name" => "_token",
                    "value" => "ZVu8AAf63UmINL4jELMwsK2hW9MA2a11mrlPTb8f",
                    "json" => 0,
                    "created_at" => "2022-08-05 11:01:09",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 260,
                    "type" => "plugin",
                    "space" => "header_menu",
                    "name" => "status",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-05 11:18:04",
                    "updated_at" => "2022-08-05 11:18:04"
                ],
                [
                    "id" => 266,
                    "type" => "system",
                    "space" => "base",
                    "name" => "footer_setting",
                    "value" => $this->getFooterSetting(),
                    "json" => 1,
                ],
                [
                    "id" => 306,
                    "type" => "plugin",
                    "space" => "latest_products",
                    "name" => "_token",
                    "value" => "a5tv1jermA4okE4DjmwQAH1WuEj7aed6ez25dDmV",
                    "json" => 0,
                    "created_at" => "2022-08-11 03:09:51",
                    "updated_at" => "2022-08-11 03:09:51"
                ],
                [
                    "id" => 307,
                    "type" => "plugin",
                    "space" => "latest_products",
                    "name" => "_method",
                    "value" => "put",
                    "json" => 0,
                    "created_at" => "2022-08-11 03:09:51",
                    "updated_at" => "2022-08-11 03:09:51"
                ],
                [
                    "id" => 435,
                    "type" => "plugin",
                    "space" => "stripe",
                    "name" => "_token",
                    "value" => "T0LajKAiOtNNb1eXK4H9lvxJupHjAqyAkzdMYS9b",
                    "json" => 0,
                    "created_at" => "2022-08-11 06:39:08",
                    "updated_at" => "2022-08-11 06:39:08"
                ],
                [
                    "id" => 436,
                    "type" => "plugin",
                    "space" => "stripe",
                    "name" => "_method",
                    "value" => "put",
                    "json" => 0,
                    "created_at" => "2022-08-11 06:39:08",
                    "updated_at" => "2022-08-11 06:39:08"
                ],
                [
                    "id" => 437,
                    "type" => "plugin",
                    "space" => "stripe",
                    "name" => "publishable_key",
                    "value" => "pk_test_Flhi0NU77hK1IBFNpl02o5hN",
                    "json" => 0,
                    "created_at" => "2022-08-11 06:39:08",
                    "updated_at" => "2022-08-11 06:39:08"
                ],
                [
                    "id" => 438,
                    "type" => "plugin",
                    "space" => "stripe",
                    "name" => "secret_key",
                    "value" => "sk_test_FlsXnYjhoqLb6d5JzvpgKdMM",
                    "json" => 0,
                    "created_at" => "2022-08-11 06:39:08",
                    "updated_at" => "2022-08-11 06:39:08"
                ],
                [
                    "id" => 439,
                    "type" => "plugin",
                    "space" => "stripe",
                    "name" => "test_mode",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-11 06:39:08",
                    "updated_at" => "2022-08-11 06:39:08"
                ],
                [
                    "id" => 441,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "_token",
                    "value" => "x7wQxPIUmuVIYEMQ7UujlczIQvuRW0uoKfmElCt7",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 442,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "_method",
                    "value" => "put",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 443,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "sandbox_client_id",
                    "value" => "AUd6ePa2vrHkWWbbl82VS9mhQ1cLlPO868bulTOgVuejU4Lt4aFHRX1rasJ8-jZmPln48iLfni8nvbn7",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 444,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "sandbox_secret",
                    "value" => "EDRgLo5BWC_SBREGRY0X1-58h4j_4lntiavsFEiAXqnorulFXYzUAFHSNNIzaE1SZomBR3ObX-26E58i",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 445,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "live_client_id",
                    "value" => "AUd6ePa2vrHkWWbbl82VS9mhQ1cLlPO868bulTOgVuejU4Lt4aFHRX1rasJ8-jZmPln48iLfni8nvbn7",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 446,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "live_secret",
                    "value" => "EDRgLo5BWC_SBREGRY0X1-58h4j_4lntiavsFEiAXqnorulFXYzUAFHSNNIzaE1SZomBR3ObX-26E58i",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 447,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "sandbox_mode",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-12 01:20:15",
                    "updated_at" => "2022-08-12 01:20:15"
                ],
                [
                    "id" => 449,
                    "type" => "system",
                    "space" => "base",
                    "name" => "logo",
                    "value" => "catalog/logo.png",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:23:33",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 450,
                    "type" => "system",
                    "space" => "base",
                    "name" => "placeholder",
                    "value" => "catalog/placeholder.png",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:27:34",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 451,
                    "type" => "system",
                    "space" => "base",
                    "name" => "favicon",
                    "value" => "catalog/favicon.png",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:32:54",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 452,
                    "type" => "system",
                    "space" => "base",
                    "name" => "meta_title",
                    "value" => "BeikeShop开源好用的跨境电商系统 - BeikeShop官网",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:48:25",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 453,
                    "type" => "system",
                    "space" => "base",
                    "name" => "meta_description",
                    "value" => "BeikeShop 是一款开源好用的跨境电商建站系统，基于 Laravel 开发。主要面向外贸，和跨境行业。系统提供商品管理、订单管理、会员管理、支付、物流、系统管理等丰富功能",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:48:25",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 454,
                    "type" => "system",
                    "space" => "base",
                    "name" => "meta_keyword",
                    "value" => "开源电商,开源代码,开源电商项目,b2b独立站,dtc独立站,跨境电商网",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:48:25",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 455,
                    "type" => "system",
                    "space" => "base",
                    "name" => "telephone",
                    "value" => "028-87966209",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:48:25",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 456,
                    "type" => "system",
                    "space" => "base",
                    "name" => "email",
                    "value" => "marketing@guangda.work",
                    "json" => 0,
                    "created_at" => "2022-08-12 03:48:25",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 462,
                    "type" => "system",
                    "space" => "base",
                    "name" => "menu_setting",
                    "value" => $this->getMenuSetting(),
                    "json" => 1,
                    "created_at" => "2022-07-14 06:19:44",
                    "updated_at" => "2022-08-29 15:13:22"
                ],
                [
                    "id" => 504,
                    "type" => "plugin",
                    "space" => "flat_shipping",
                    "name" => "_token",
                    "value" => "NfoFyPbATfYS57KmHyHZgJJLubz78Ngv8uDTKYjL",
                    "json" => 0,
                    "created_at" => "2022-08-22 14:41:00",
                    "updated_at" => "2022-08-22 14:41:00"
                ],
                [
                    "id" => 505,
                    "type" => "plugin",
                    "space" => "flat_shipping",
                    "name" => "_method",
                    "value" => "put",
                    "json" => 0,
                    "created_at" => "2022-08-22 14:41:00",
                    "updated_at" => "2022-08-22 14:41:00"
                ],
                [
                    "id" => 506,
                    "type" => "plugin",
                    "space" => "flat_shipping",
                    "name" => "type",
                    "value" => "percent",
                    "json" => 0,
                    "created_at" => "2022-08-22 14:41:00",
                    "updated_at" => "2022-08-22 14:41:00"
                ],
                [
                    "id" => 507,
                    "type" => "plugin",
                    "space" => "flat_shipping",
                    "name" => "value",
                    "value" => "10",
                    "json" => 0,
                    "created_at" => "2022-08-22 14:41:00",
                    "updated_at" => "2022-08-22 14:41:00"
                ],
                [
                    "id" => 509,
                    "type" => "system",
                    "space" => "base",
                    "name" => "default_customer_group_id",
                    "value" => "36",
                    "json" => 0,
                    "created_at" => "2022-08-23 10:10:46",
                    "updated_at" => "2022-08-31 18:14:36"
                ],
                [
                    "id" => 516,
                    "type" => "plugin",
                    "space" => "stripe",
                    "name" => "status",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-25 16:12:28",
                    "updated_at" => "2022-08-25 16:12:28"
                ],
                [
                    "id" => 517,
                    "type" => "plugin",
                    "space" => "latest_products",
                    "name" => "status",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-25 16:12:32",
                    "updated_at" => "2022-08-25 16:12:32"
                ],
                [
                    "id" => 518,
                    "type" => "plugin",
                    "space" => "flat_shipping",
                    "name" => "status",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-25 16:12:34",
                    "updated_at" => "2022-08-25 16:12:34"
                ],
                [
                    "id" => 519,
                    "type" => "plugin",
                    "space" => "paypal",
                    "name" => "status",
                    "value" => "1",
                    "json" => 0,
                    "created_at" => "2022-08-25 16:12:55",
                    "updated_at" => "2022-08-25 16:12:55"
                ]
            ];
    }


    private function getMenuSetting()
    {
        // $json = "{\"menus\":[{\"isFull\":false,\"badge\":{\"isShow\":false,\"name\":{\"en\":\"HOT\",\"zh_cn\":\"\u70ed\u5356\"],\"bg_color\":\"#fd560f\",\"text_color\":\"#ffffff\"],\"link\":{\"type\":\"page\",\"value\":\"\",\"text\":[],\"link\":\"\"],\"name\":{\"en\":\"Sports\",\"zh_cn\":\"\u8fd0\u52a8\u6f6e\u5427\"],\"isChildren\":false,\"childrenGroup\":[{\"name\":{\"en\":\"leading the fashion\",\"zh_cn\":\"\u5f15\u9886\u65f6\u5c1a\"],\"type\":\"\",\"image\":{\"image\":[],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[]}],\"children\":[{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":3,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100000,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":3,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100008,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":5,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}}]],{\"name\":{\"en\":\"Special offer\",\"zh_cn\":\"\u7279\u4ef7\u4f18\u60e0\"],\"type\":\"link\",\"image\":{\"image\":[],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[]}],\"children\":[{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100000,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100008,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":6,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100003,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100009,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}}]],{\"name\":{\"en\":\"Popular products\",\"zh_cn\":\"\u7206\u6b3e\u5546\u54c1\"],\"type\":\"image\",\"image\":{\"image\":{\"en\":\"catalog\/demo\/banner\/2_en.jpg\",\"zh_cn\":\"catalog\/demo\/banner\/2.jpg\"],\"link\":{\"type\":\"product\",\"value\":1,\"text\":[],\"link\":\"\"}],\"children\":[]}]],{\"isFull\":false,\"badge\":{\"isShow\":false,\"name\":{\"en\":\"\",\"zh_cn\":\"\"],\"bg_color\":\"#fd560f\",\"text_color\":\"#ffffff\"],\"link\":{\"type\":\"page\",\"value\":\"\",\"text\":[],\"link\":\"\"],\"name\":{\"en\":\"3C\",\"zh_cn\":\"\u7535\u5b50\u6570\u7801\"],\"isChildren\":false,\"childrenGroup\":[]],{\"isFull\":false,\"badge\":{\"isShow\":false,\"name\":{\"en\":\"NEW\",\"zh_cn\":\"\u65b0\u54c1\"],\"bg_color\":\"#7628A2\",\"text_color\":\"#ffffff\"],\"link\":{\"type\":\"page\",\"value\":\"\",\"text\":[],\"link\":\"\"],\"name\":{\"en\":\"Fashion\",\"zh_cn\":\"\u65f6\u5c1a\u6f6e\u6d41\"],\"isChildren\":false,\"childrenGroup\":[{\"name\":{\"en\":\"global purchase\",\"zh_cn\":\"\u5168\u7403\u8d2d\"],\"type\":\"link\",\"image\":{\"image\":[],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[]}],\"children\":[{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":2,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\",\"new_window\":true}],{\"name\":[],\"link\":{\"type\":\"product\",\"value\":1,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100001,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"page\",\"value\":21,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}}]],{\"name\":{\"en\":\"Fashion\",\"zh_cn\":\"\u65f6\u5c1a\"],\"type\":\"image\",\"image\":{\"image\":{\"en\":\"catalog\/demo\/product\/16.jpg\",\"zh_cn\":\"catalog\/demo\/product\/16.jpg\"],\"link\":{\"type\":\"category\",\"value\":100001,\"text\":[],\"link\":\"\"}],\"children\":[{\"name\":[],\"link\":{\"type\":\"custom\",\"value\":\"https:\/\/www.baidu.com\",\"text\":{\"en\":\"\",\"zh_cn\":\"baidu \"],\"link\":\"\",\"new_window\":true}}]],{\"name\":{\"en\":\"recommended\",\"zh_cn\":\"\u5e97\u957f\u63a8\u8350\"],\"type\":\"image\",\"image\":{\"image\":{\"en\":\"catalog\/demo\/product\/13.jpg\",\"zh_cn\":\"catalog\/demo\/product\/13.jpg\"],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[],\"link\":\"\"}],\"children\":[]}]],{\"isFull\":false,\"badge\":{\"isShow\":false,\"name\":{\"en\":\"\",\"zh_cn\":\"\"],\"bg_color\":\"#fd560f\",\"text_color\":\"#ffffff\"],\"link\":{\"type\":\"page\",\"value\":\"\",\"text\":[],\"link\":\"\"],\"name\":{\"en\":\"Digital\",\"zh_cn\":\"\u6570\u7801\u4ea7\u54c1\"],\"isChildren\":false,\"childrenGroup\":[{\"name\":{\"en\":\"\u5927\u724c\u4fc3\u9500\",\"zh_cn\":\"\u5927\u724c\u4fc3\u9500\"],\"type\":\"\",\"image\":{\"image\":[],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[]}],\"children\":[{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":2,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":8,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":9,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":1,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":6,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}}]],{\"name\":{\"en\":\"\",\"zh_cn\":\"\u6d3b\u52a8\u793c\u54c1\"],\"type\":\"\",\"image\":{\"image\":[],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[]}],\"children\":[{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100000,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100002,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100001,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"category\",\"value\":100007,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"static\",\"value\":\"account.order.index\",\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}}]],{\"name\":{\"en\":\"\",\"zh_cn\":\"\u5168\u573a\u4e09\u6298\"],\"type\":\"\",\"image\":{\"image\":[],\"link\":{\"type\":\"product\",\"value\":\"\",\"text\":[]}],\"children\":[{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":2,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":6,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":10,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":3,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}],{\"name\":[],\"link\":{\"type\":\"brand\",\"value\":1,\"text\":{\"en\":\"\",\"zh_cn\":\"\"],\"link\":\"\"}}]}]],{\"isFull\":false,\"badge\":{\"isShow\":false,\"name\":{\"en\":\"\",\"zh_cn\":\"\"],\"bg_color\":null,\"text_color\":\"#ffffff\"],\"link\":{\"type\":\"page\",\"value\":\"\",\"text\":[],\"link\":\"\"],\"name\":{\"en\":\"Hot Deals\",\"zh_cn\":\"\u70ed\u5356\u7279\u60e0\"],\"isChildren\":false,\"childrenGroup\":[]],{\"isFull\":false,\"badge\":{\"isShow\":false,\"name\":{\"en\":\"Sales\",\"zh_cn\":\"\u5927\u724c\u76f4\u9500\"],\"bg_color\":\"#00C5C5\",\"text_color\":\"#ffffff\"],\"link\":{\"type\":\"page\",\"value\":\"\",\"text\":[],\"link\":\"\"],\"name\":{\"en\":\"Brand\",\"zh_cn\":\"\u56fd\u9645\u6f6e\u724c\"],\"isChildren\":false,\"childrenGroup\":[]}]}";
        $data = [
            "menus" => [
                [
                    "isFull" => false,
                    "badge" => [
                        "isShow" => false,
                        "name" => [
                            "en" => "HOT",
                            "zh_cn" => "热卖"
                        ],
                        "bg_color" => "#fd560f",
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "page",
                        "value" => "",
                        "text" => [
                        ],
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
                                "image" => [
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => [
                                    ]
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100000,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 5,
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
                                "image" => [
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => [
                                    ]
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100000,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100009,
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
                                    "text" => [
                                    ],
                                    "link" => ""
                                ]
                            ],
                            "children" => [
                            ]
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
                        "type" => "page",
                        "value" => "",
                        "text" => [
                        ],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "3C",
                        "zh_cn" => "电子数码"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => [
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
                        "type" => "page",
                        "value" => "",
                        "text" => [
                        ],
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
                                "image" => [
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => [
                                    ]
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 2,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => "",
                                        "new_window" => true
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "product",
                                        "value" => 1,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100001,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
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
                                    "type" => "category",
                                    "value" => 100001,
                                    "text" => [
                                    ],
                                    "link" => ""
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
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
                                    "value" => "",
                                    "text" => [
                                    ],
                                    "link" => ""
                                ]
                            ],
                            "children" => [
                            ]
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
                        "type" => "page",
                        "value" => "",
                        "text" => [
                        ],
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
                                "en" => "大牌促销",
                                "zh_cn" => "大牌促销"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => [
                                    ]
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                "en" => "",
                                "zh_cn" => "活动礼品"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => [
                                    ]
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100000,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100002,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100001,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "category",
                                        "value" => 100007,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "static",
                                        "value" => "account.order.index",
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
                                "en" => "",
                                "zh_cn" => "全场三折"
                            ],
                            "type" => "",
                            "image" => [
                                "image" => [
                                ],
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "text" => [
                                    ]
                                ]
                            ],
                            "children" => [
                                [
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 10,
                                        "text" => [
                                            "en" => "",
                                            "zh_cn" => ""
                                        ],
                                        "link" => ""
                                    ]
                                ],
                                [
                                    "name" => [
                                    ],
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
                                    "name" => [
                                    ],
                                    "link" => [
                                        "type" => "brand",
                                        "value" => 1,
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
                            "en" => "",
                            "zh_cn" => ""
                        ],
                        "bg_color" => null,
                        "text_color" => "#ffffff"
                    ],
                    "link" => [
                        "type" => "page",
                        "value" => "",
                        "text" => [
                        ],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Hot Deals",
                        "zh_cn" => "热卖特惠"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => [
                    ]
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
                        "type" => "page",
                        "value" => "",
                        "text" => [
                        ],
                        "link" => ""
                    ],
                    "name" => [
                        "en" => "Brand",
                        "zh_cn" => "国际潮牌"
                    ],
                    "isChildren" => false,
                    "childrenGroup" => [
                    ]
                ]
            ]
        ];
        return json_encode($data);
    }

    private function getHomeSetting()
    {
        $data = [
            "modules" => [
                [
                    "content" => [
                        "style" => [
                            "background_color" => ""
                        ],
                        "full" => true,
                        "floor" => [
                            "2" => "",
                            "3" => ""
                        ],
                        "images" => [
                            [
                                "image" => [
                                    "en" => "catalog/demo/banner/banner-4-en.jpg",
                                    "zh_cn" => "catalog/demo/banner/banner-4.jpg"
                                ],
                                "show" => true,
                                "link" => [
                                    "type" => "category",
                                    "value" => 100012,
                                    "link" => ""
                                ]
                            ],
                            [
                                "image" => [
                                    "en" => "catalog/demo/banner/banner-3-en.jpg",
                                    "zh_cn" => "catalog/demo/banner/banner-3.jpg"
                                ],
                                "show" => false,
                                "link" => [
                                    "type" => "category",
                                    "value" => 100008,
                                    "link" => ""
                                ]
                            ]
                        ]
                    ],
                    "code" => "slideshow",
                    "name" => "幻灯片",
                    "module_id" => "b0448efb0989"
                ],
                [
                    "code" => "image401",
                    "content" => [
                        "style" => [
                            "background_color" => ""
                        ],
                        "floor" => [
                            "zh_cn" => "",
                            "en" => ""
                        ],
                        "images" => [
                            [
                                "image" => [
                                    "en" => "catalog/demo/image_plus_1-en.png",
                                    "zh_cn" => "catalog/demo/image_plus_1.png"
                                ],
                                "show" => false,
                                "link" => [
                                    "type" => "category",
                                    "value" => 100000,
                                    "link" => ""
                                ]
                            ],
                            [
                                "image" => [
                                    "en" => "catalog/demo/image_plus_2-en.png",
                                    "zh_cn" => "catalog/demo/image_plus_2.png"
                                ],
                                "show" => false,
                                "link" => [
                                    "type" => "category",
                                    "value" => 100007,
                                    "link" => ""
                                ]
                            ],
                            [
                                "image" => [
                                    "en" => "catalog/demo/image_plus_3-en.png",
                                    "zh_cn" => "catalog/demo/image_plus_3.png"
                                ],
                                "show" => false,
                                "link" => [
                                    "type" => "product",
                                    "value" => "",
                                    "link" => ""
                                ]
                            ],
                            [
                                "image" => [
                                    "en" => "catalog/demo/image_plus_4-en.png",
                                    "zh_cn" => "catalog/demo/image_plus_4.png"
                                ],
                                "show" => true,
                                "link" => [
                                    "type" => "product",
                                    "value" => 2,
                                    "link" => ""
                                ]
                            ]
                        ]
                    ],
                    "module_id" => "g1vrlixfzfjk9a3k",
                    "name" => "一行四图-pro"
                ],
                [
                    "code" => "tab_product",
                    "content" => [
                        "style" => [
                            "background_color" => ""
                        ],
                        "floor" => [
                            "zh_cn" => "",
                            "en" => ""
                        ],
                        "tabs" => [
                            [
                                "title" => [
                                    "en" => "latest promotions",
                                    "zh_cn" => "最新促销"
                                ],
                                "products" => [
                                    1,
                                    2,
                                    3,
                                    4,
                                    5,
                                    7,
                                    8,
                                    11
                                ]
                            ],
                            [
                                "title" => [
                                    "en" => "Fashion sheet",
                                    "zh_cn" => "时尚单品"
                                ],
                                "products" => [
                                    6,
                                    49,
                                    328,
                                    9,
                                    10,
                                    11,
                                    12,
                                    13,
                                    14,
                                    15
                                ]
                            ]
                        ],
                        "title" => [
                            "en" => "Recommended products",
                            "zh_cn" => "推荐商品"
                        ]
                    ],
                    "module_id" => "s6e7e3vucriazzbi",
                    "name" => "选项卡商品"
                ],
                [
                    "code" => "image100",
                    "content" => [
                        "style" => [
                            "background_color" => ""
                        ],
                        "floor" => [
                            "zh_cn" => "",
                            "en" => ""
                        ],
                        "full" => true,
                        "images" => [
                            [
                                "image" => [
                                    "en" => "catalog/demo/banner/banner-2-en.png",
                                    "zh_cn" => "catalog/demo/banner/banner-2.png"
                                ],
                                "show" => true,
                                "link" => [
                                    "type" => "category",
                                    "value" => 100003,
                                    "link" => ""
                                ]
                            ]
                        ]
                    ],
                    "module_id" => "0htwy33z3xcituyx",
                    "name" => "单图模块"
                ],
                [
                    "code" => "brand",
                    "content" => [
                        "style" => [
                            "background_color" => ""
                        ],
                        "floor" => [
                            "en" => "",
                            "zh_cn" => ""
                        ],
                        "full" => true,
                        "title" => [
                            "en" => "Recommended Brand",
                            "zh_cn" => "推荐品牌"
                        ],
                        "brands" => [
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
                    "module_id" => "yln7isoqlxovqz3k",
                    "name" => "品牌模块"
                ]
            ]
        ];
        return json_encode($data);
    }

    private function getFooterSetting()
    {
        $data = [
            "content" => [
                "link1" => [
                    "title" => [
                        "zh_cn" => "关于我们",
                        "en" => "About us"
                    ],
                    "links" => [
                        [
                            "text" => [
                                "zh_cn" => "关于我们",
                                "en" => "about us"
                            ],
                            "value" => 21,
                            "type" => "page",
                            "link" => ""
                        ],
                        [
                            "link" => "",
                            "value" => 18,
                            "type" => "page",
                            "text" => [

                            ]
                        ],
                        [
                            "link" => "",
                            "value" => 12,
                            "type" => "page",
                            "text" => [

                            ]
                        ],
                        [
                            "link" => "",
                            "value" => "account.order.index",
                            "type" => "static",
                            "text" => [
                                "zh_cn" => "",
                                "en" => ""
                            ]
                        ]
                    ]
                ],
                "link3" => [
                    "title" => [
                        "zh_cn" => "其他",
                        "en" => "Other"
                    ],
                    "links" => [
                        [
                            "link" => "",
                            "value" => "brands.index",
                            "type" => "static",
                            "text" => [

                            ]
                        ],
                        [
                            "link" => "",
                            "value" => "account.index",
                            "type" => "static",
                            "text" => [
                                "zh_cn" => "",
                                "en" => ""
                            ]
                        ],
                        [
                            "link" => "",
                            "value" => 20,
                            "type" => "page",
                            "text" => [
                                "zh_cn" => "",
                                "en" => ""
                            ]
                        ],
                        [
                            "link" => "",
                            "value" => 21,
                            "type" => "page",
                            "text" => [
                                "zh_cn" => "",
                                "en" => ""
                            ]
                        ]
                    ]
                ],
                "intro" => [
                    "social_network" => [

                    ],
                    "logo" => "catalog/logo.png",
                    "text" => [
                        "zh_cn" => "<p style=\"line-height: 1.4;\"><strong>成都光大网络科技有限公司</strong></p>\n<p style=\"line-height: 1.4;\">是一家主要从事互联网开发的高科技企业,<\/p>\n<p style=\"line-height: 1.4;\">公司成立于2014年8月，公司以为客户创造</p>\n<p style=\"line-height: 1.4;\">价值为核心价值观，帮助中小企业利用互联</p>\n<p style=\"line-height: 1.4;\">网工具提升产品销售为目标。</p>",
                        "en" => "<p>Chengdu Guangda Network Technology Co., Ltd. is a high-tech enterprise mainly engaged in Internet development. The company was established in August 2014.</p>"
                    ]
                ],
                "link2" => [
                    "title" => [
                        "zh_cn" => "会员中心",
                        "en" => "Account"
                    ],
                    "links" => [
                        [
                            "link" => "",
                            "value" => "account.index",
                            "type" => "static",
                            "text" => [

                            ]
                        ],
                        [
                            "link" => "",
                            "value" => "account.order.index",
                            "type" => "static",
                            "text" => [

                            ]
                        ],
                        [
                            "link" => "",
                            "value" => "account.wishlist.index",
                            "type" => "static",
                            "text" => [

                            ]
                        ],
                        [
                            "link" => "",
                            "value" => "brands.index",
                            "type" => "static",
                            "text" => [
                                "zh_cn" => "",
                                "en" => ""
                            ]
                        ]
                    ]
                ],
                "contact" => [
                    "address" => [
                        "zh_cn" => "成都市高新区益州大道中段1858号天府软件园G8 成都光大网络科技有限公司",
                        "en" => "G8 Tianfu Software Park Chengdu China, Guangda Network Technology Co., Ltd."
                    ],
                    "email" => "Marketing@guangda.work",
                    "telephone" => "028-87966209"
                ]
            ],
            "bottom" => [
                "copyright" => [
                    "zh_cn" => "<div>技术支持 <a href=\"https://beikeshop.com/\" target=\"_blank\" rel=\"noopener\">beikeshop.com</a>&nbsp; - 成都光大网络科技 &copy; 2022</div>",
                    "en" => "<div>Technical Support <a href=\"https://beikeshop.com/\" target=\"_blank\" rel=\"noopener\">beikeshop.com</a>&nbsp; - Chengdu Guangda Network Technology &copy; 2022</div>"
                ],
                "image" => "catalog/demo/banner/pay_icons.png"
            ],
            "services" => [
                "enable" => true,
                "items" => [
                    [
                        "title" => [
                            "zh_cn" => "物行天下",
                            "en" => "Material world"
                        ],
                        "show" => false,
                        "image" => "catalog/demo/services-icon/4.png",
                        "sub_title" => [
                            "zh_cn" => "多仓直发 极速配送多仓直发 极速配送",
                            "en" => "Multi - warehouse fast delivery"
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" => "退换无忧",
                            "en" => "Return all"
                        ],
                        "show" => false,
                        "image" => "catalog/demo/services-icon/3.png",
                        "sub_title" => [
                            "zh_cn" => "放心购物 退还无忧放心购物 退还无忧",
                            "en" => "Rest assured shopping return worry"
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" => "精致服务",
                            "en" => "Delicate service"
                        ],
                        "show" => false,
                        "image" => "catalog/demo/services-icon/1.png",
                        "sub_title" => [
                            "zh_cn" => "精致服务 售后保障精致服务 售后保障",
                            "en" => "Exquisite service and after-sales guarantee"
                        ]
                    ],
                    [
                        "title" => [
                            "zh_cn" => "满减活动",
                            "en" => "With reduced activity"
                        ],
                        "show" => true,
                        "image" => "catalog/demo/services-icon/2.png",
                        "sub_title" => [
                            "zh_cn" => "满500元立减90，新用户立减200",
                            "en" => "If 500 yuan is exceeded, a reduction of 90 yuan will be given"
                        ]
                    ]
                ]
            ]
        ];
        return json_encode($data);
    }
}
