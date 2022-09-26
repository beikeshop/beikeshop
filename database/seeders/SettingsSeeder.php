<?php
/**
 * SettingsSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
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
        return [
            ["type" => "system", "space" => "base", "name" => "country_id", "value" => "44", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "locale", "value" => "en", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "design_setting", "value" => $this->getHomeSetting(), "json" => 1],
            ["type" => "system", "space" => "base", "name" => "theme", "value" => "default", "json" => 0],
            ["type" => "plugin", "space" => "service_charge", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "status", "value" => "", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "admin_name", "value" => "admin", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "tax", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "tax_address", "value" => "payment", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "currency", "value" => "USD", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "zone_id", "value" => "710", "json" => 0],
            ["type" => "plugin", "space" => "header_menu", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "footer_setting", "value" => $this->getFooterSetting(),
                "json" => 1],
            ["type" => "plugin", "space" => "stripe", "name" => "publishable_key", "value" => "pk_test_Flhi0NU77hK1IBFNpl02o5hN", "json" => 0],
            ["type" => "plugin", "space" => "stripe", "name" => "secret_key", "value" => "sk_test_FlsXnYjhoqLb6d5JzvpgKdMM", "json" => 0],
            ["type" => "plugin", "space" => "stripe", "name" => "test_mode", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "sandbox_client_id", "value" => "AUd6ePa2vrHkWWbbl82VS9mhQ1cLlPO868bulTOgVuejU4Lt4aFHRX1rasJ8-jZmPln48iLfni8nvbn7", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "sandbox_secret", "value" => "EDRgLo5BWC_SBREGRY0X1-58h4j_4lntiavsFEiAXqnorulFXYzUAFHSNNIzaE1SZomBR3ObX-26E58i", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "live_client_id", "value" => "AUd6ePa2vrHkWWbbl82VS9mhQ1cLlPO868bulTOgVuejU4Lt4aFHRX1rasJ8-jZmPln48iLfni8nvbn7", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "live_secret", "value" => "EDRgLo5BWC_SBREGRY0X1-58h4j_4lntiavsFEiAXqnorulFXYzUAFHSNNIzaE1SZomBR3ObX-26E58i", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "sandbox_mode", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "logo", "value" => "catalog/logo.png", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "placeholder", "value" => "catalog/placeholder.png", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "favicon", "value" => "catalog/favicon.png", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "meta_title", "value" => "BeikeShop开源好用的跨境电商系统 - BeikeShop官网", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "meta_description", "value" => "BeikeShop 是一款开源好用的跨境电商建站系统，基于 Laravel 开发。主要面向外贸，和跨境行业。系统提供商品管理、订单管理、会员管理、支付、物流、系统管理等丰富功能", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "meta_keyword", "value" => "开源电商,开源代码,开源电商项目,b2b独立站,dtc独立站,跨境电商网", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "telephone", "value" => "028-87966209", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "email", "value" => "marketing@guangda.work", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "menu_setting", "value" => $this->getMenuSetting(), "json" => 1],
            ["type" => "plugin", "space" => "flat_shipping", "name" => "type", "value" => "percent", "json" => 0],
            ["type" => "plugin", "space" => "flat_shipping", "name" => "value", "value" => "10", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "default_customer_group_id", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "stripe", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "latest_products", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "flat_shipping", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "status", "value" => "1", "json" => 0]
        ];
    }


    /**
     * 设置菜单数据
     *
     * @return false|string
     * @throws \Exception
     */
    private function getMenuSetting(): bool|string
    {
        $json = '{"menus":[{"isFull":false,"badge":{"isShow":false,"name":{"en":"","zh_cn":""},"bg_color":"#fd560f","text_color":"#ffffff"},"link":{"type":"page","value":"","text":[],"link":""},"name":{"en":"Sports","zh_cn":"\u8fd0\u52a8\u6f6e\u5427"},"isChildren":false,"childrenGroup":[{"name":{"en":"leading the fashion","zh_cn":"\u5f15\u9886\u65f6\u5c1a"},"type":"","image":{"image":[],"link":{"type":"product","value":"","text":[]}},"children":[{"name":[],"link":{"type":"brand","value":3,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100008,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":5,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100003,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100010,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100010,"text":{"en":"","zh_cn":""},"link":""}}]},{"name":{"en":"Special offer","zh_cn":"\u7279\u4ef7\u4f18\u60e0"},"type":"link","image":{"image":[],"link":{"type":"product","value":"","text":[]}},"children":[{"name":[],"link":{"type":"category","value":100008,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":6,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100003,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":7,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":8,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":2,"text":{"en":"","zh_cn":""},"link":""}}]},{"name":{"en":"Popular products","zh_cn":"\u7206\u6b3e\u5546\u54c1"},"type":"image","image":{"image":{"en":"catalog\/demo\/banner\/2_en.jpg","zh_cn":"catalog\/demo\/banner\/2.jpg"},"link":{"type":"product","value":1,"text":[],"link":""}},"children":[]}]},{"isFull":false,"badge":{"isShow":false,"name":{"en":"NEW","zh_cn":"\u65b0\u54c1"},"bg_color":"#7628A2","text_color":"#ffffff"},"link":{"type":"category","value":100003,"text":[],"link":""},"name":{"en":"Fashion","zh_cn":"\u65f6\u5c1a\u6f6e\u6d41"},"isChildren":false,"childrenGroup":[{"name":{"en":"global purchase","zh_cn":"\u5168\u7403\u8d2d"},"type":"link","image":{"image":[],"link":{"type":"product","value":"","text":[]}},"children":[{"name":[],"link":{"type":"brand","value":8,"text":{"en":"","zh_cn":""},"link":"","new_window":true}},{"name":[],"link":{"type":"category","value":100003,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":7,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"page","value":21,"text":{"en":"","zh_cn":""},"link":""}}]},{"name":{"en":"Fashion","zh_cn":"\u65f6\u5c1a"},"type":"image","image":{"image":{"en":"catalog\/demo\/product\/16.jpg","zh_cn":"catalog\/demo\/product\/16.jpg"},"link":{"type":"product","value":4,"text":[],"link":""}},"children":[{"name":[],"link":{"type":"custom","value":"https:\/\/www.baidu.com","text":{"en":"","zh_cn":"baidu "},"link":"","new_window":true}}]},{"name":{"en":"recommended","zh_cn":"\u5e97\u957f\u63a8\u8350"},"type":"image","image":{"image":{"en":"catalog\/demo\/product\/13.jpg","zh_cn":"catalog\/demo\/product\/13.jpg"},"link":{"type":"product","value":2,"text":[],"link":""}},"children":[]}]},{"isFull":false,"badge":{"isShow":false,"name":{"en":"","zh_cn":""},"bg_color":"#fd560f","text_color":"#ffffff"},"link":{"type":"category","value":100007,"text":[],"link":""},"name":{"en":"Digital","zh_cn":"\u6570\u7801\u4ea7\u54c1"},"isChildren":false,"childrenGroup":[{"name":{"en":"Big promotion","zh_cn":"\u5927\u724c\u4fc3\u9500"},"type":"","image":{"image":[],"link":{"type":"product","value":"","text":[]}},"children":[{"name":[],"link":{"type":"brand","value":2,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":8,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":9,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":1,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":6,"text":{"en":"","zh_cn":""},"link":""}}]},{"name":{"en":"Activity of gift","zh_cn":"\u6d3b\u52a8\u793c\u54c1"},"type":"","image":{"image":[],"link":{"type":"product","value":"","text":[]}},"children":[{"name":[],"link":{"type":"category","value":100003,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100006,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100012,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100006,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"category","value":100010,"text":{"en":"","zh_cn":""},"link":""}}]},{"name":{"en":"All three fold","zh_cn":"\u5168\u573a\u4e09\u6298"},"type":"","image":{"image":[],"link":{"type":"product","value":"","text":[]}},"children":[{"name":[],"link":{"type":"brand","value":1,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":3,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":7,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":8,"text":{"en":"","zh_cn":""},"link":""}},{"name":[],"link":{"type":"brand","value":9,"text":{"en":"","zh_cn":""},"link":""}}]}]},{"isFull":false,"badge":{"isShow":false,"name":{"en":"Hot","zh_cn":"\u70ed\u5356"},"bg_color":"#FF4D00","text_color":"#ffffff"},"link":{"type":"page","value":"","text":[],"link":""},"name":{"en":"Hot","zh_cn":"\u70ed\u5356\u7279\u60e0"},"isChildren":false,"childrenGroup":[]},{"isFull":false,"badge":{"isShow":false,"name":{"en":"Sales","zh_cn":"\u5927\u724c\u76f4\u9500"},"bg_color":"#00C5C5","text_color":"#ffffff"},"link":{"type":"page","value":"","text":[],"link":""},"name":{"en":"Brand","zh_cn":"\u56fd\u9645\u6f6e\u724c"},"isChildren":false,"childrenGroup":[]}]}';
        $setting = json_decode($json, true);
        if (empty($setting)) {
            throw new \Exception("无效的菜单设置");
        }
        return json_encode($setting);
    }


    /**
     * 设置首页装修数据
     *
     * @return false|string
     * @throws \Exception
     */
    private function getHomeSetting(): bool|string
    {
        $json = '{"modules":[{"content":{"style":{"background_color":""},"full":true,"floor":{"2":"","3":""},"images":[{"image":{"en":"catalog\/demo\/banner\/banner-4-en.jpg","zh_cn":"catalog\/demo\/banner\/banner-4.jpg"},"show":true,"link":{"type":"category","value":100012,"link":""}},{"image":{"en":"catalog\/demo\/banner\/banner-3-en.jpg","zh_cn":"catalog\/demo\/banner\/banner-3.jpg"},"show":false,"link":{"type":"category","value":100008,"link":""}}]},"code":"slideshow","name":"\u5e7b\u706f\u7247","module_id":"b0448efb0989"},{"code":"image401","content":{"style":{"background_color":""},"floor":{"zh_cn":"","en":""},"images":[{"image":{"en":"catalog\/demo\/image_plus_1-en.png","zh_cn":"catalog\/demo\/image_plus_1.png"},"show":false,"link":{"type":"category","value":100000,"link":""}},{"image":{"en":"catalog\/demo\/image_plus_2-en.png","zh_cn":"catalog\/demo\/image_plus_2.png"},"show":false,"link":{"type":"category","value":100007,"link":""}},{"image":{"en":"catalog\/demo\/image_plus_3-en.png","zh_cn":"catalog\/demo\/image_plus_3.png"},"show":false,"link":{"type":"product","value":"","link":""}},{"image":{"en":"catalog\/demo\/image_plus_4-en.png","zh_cn":"catalog\/demo\/image_plus_4.png"},"show":true,"link":{"type":"product","value":2,"link":""}}]},"module_id":"g1vrlixfzfjk9a3k","name":"\u4e00\u884c\u56db\u56fe-pro"},{"code":"tab_product","content":{"style":{"background_color":""},"floor":{"zh_cn":"","en":""},"tabs":[{"title":{"en":"latest promotions","zh_cn":"\u6700\u65b0\u4fc3\u9500"},"products":[1,2,3,4,5,7,8,11]},{"title":{"en":"Fashion sheet","zh_cn":"\u65f6\u5c1a\u5355\u54c1"},"products":[6,49,328,9,10,11,12,13,14,15]}],"title":{"en":"Recommended products","zh_cn":"\u63a8\u8350\u5546\u54c1"}},"module_id":"s6e7e3vucriazzbi","name":"\u9009\u9879\u5361\u5546\u54c1"},{"code":"image100","content":{"style":{"background_color":""},"floor":{"zh_cn":"","en":""},"full":true,"images":[{"image":{"en":"catalog\/demo\/banner\/banner-2-en.png","zh_cn":"catalog\/demo\/banner\/banner-2.png"},"show":true,"link":{"type":"category","value":100003,"link":""}}]},"module_id":"0htwy33z3xcituyx","name":"\u5355\u56fe\u6a21\u5757"},{"code":"brand","content":{"style":{"background_color":""},"floor":{"en":"","zh_cn":""},"full":true,"title":{"en":"Recommended Brand","zh_cn":"\u63a8\u8350\u54c1\u724c"},"brands":[1,2,3,4,5,6,7,8,9,10,11,12]},"module_id":"yln7isoqlxovqz3k","name":"\u54c1\u724c\u6a21\u5757"}]}';
        $setting = json_decode($json, true);
        if (empty($setting)) {
            throw new \Exception("无效的首页装修设置");
        }
        return json_encode($setting);
    }


    /**
     * 设置页尾数据
     *
     * @return false|string
     * @throws \Exception
     */
    private function getFooterSetting(): bool|string
    {
        $json = '{"services":{"enable":true,"items":[{"image":"catalog\/demo\/services-icon\/4.png","title":{"en":"Material world","zh_cn":"\u7269\u884c\u5929\u4e0b"},"sub_title":{"en":"Multi - warehouse fast delivery","zh_cn":"\u591a\u4ed3\u76f4\u53d1 \u6781\u901f\u914d\u9001\u591a\u4ed3\u76f4\u53d1 \u6781\u901f\u914d\u9001"},"show":false},{"image":"catalog\/demo\/services-icon\/3.png","title":{"en":"Return all","zh_cn":"\u9000\u6362\u65e0\u5fe7"},"sub_title":{"en":"Rest assured shopping return worry","zh_cn":"\u653e\u5fc3\u8d2d\u7269 \u9000\u8fd8\u65e0\u5fe7\u653e\u5fc3\u8d2d\u7269 \u9000\u8fd8\u65e0\u5fe7"},"show":false},{"image":"catalog\/demo\/services-icon\/1.png","title":{"en":"Delicate service","zh_cn":"\u7cbe\u81f4\u670d\u52a1"},"sub_title":{"en":"Exquisite service and after-sales guarantee","zh_cn":"\u7cbe\u81f4\u670d\u52a1 \u552e\u540e\u4fdd\u969c\u7cbe\u81f4\u670d\u52a1 \u552e\u540e\u4fdd\u969c"},"show":false},{"image":"catalog\/demo\/services-icon\/2.png","title":{"en":"With reduced activity","zh_cn":"\u6ee1\u51cf\u6d3b\u52a8"},"sub_title":{"en":"If 500 yuan is exceeded, a reduction of 90 yuan will be given","zh_cn":"\u6ee1500\u5143\u7acb\u51cf90\uff0c\u65b0\u7528\u6237\u7acb\u51cf200"},"show":true}]},"content":{"intro":{"logo":"catalog\/logo.png","text":{"en":"<p>Chengdu Guangda Network Technology Co., Ltd. is a high-tech enterprise mainly engaged in Internet development. The company was established in August 2014.<\/p>","zh_cn":"<p style=\"line-height: 1.4;\"><strong>\u6210\u90fd\u5149\u5927\u7f51\u7edc\u79d1\u6280\u6709\u9650\u516c\u53f8<\/strong><\/p>\n<p style=\"line-height: 1.4;\">\u662f\u4e00\u5bb6\u4e13\u4e1a\u4e92\u8054\u7f51\u5f00\u53d1\u7684\u9ad8\u79d1\u6280\u4f01\u4e1a\uff0c\u516c\u53f8\u6210\u7acb\u4e8e2014\u5e748\u6708\u3002<\/p>\n<p style=\"line-height: 1.4;\">\u516c\u53f8\u4ee5\u4e3a\u5ba2\u6237\u521b\u9020\u4ef7\u503c\u4e3a\u6838\u5fc3\u4ef7\u503c\u89c2\uff0c\u5e2e\u52a9\u4e2d\u5c0f\u4f01\u4e1a\u5229\u7528\u4e92\u8054\u7f51\u5de5\u5177\u63d0\u5347\u4ea7\u54c1\u9500\u552e\u3002<\/p>"},"social_network":[]},"link1":{"title":{"en":"About us","zh_cn":"\u5173\u4e8e\u6211\u4eec"},"links":[{"link":"","type":"page","value":21,"text":{"en":"about us","zh_cn":"\u5173\u4e8e\u6211\u4eec"}},{"type":"page","value":18,"text":[],"link":""},{"type":"page","value":12,"text":[],"link":""},{"type":"static","value":"account.order.index","text":{"en":"","zh_cn":""},"link":""}]},"link2":{"title":{"en":"Account","zh_cn":"\u4f1a\u5458\u4e2d\u5fc3"},"links":[{"type":"static","value":"account.index","text":[],"link":""},{"type":"static","value":"account.order.index","text":[],"link":""},{"type":"static","value":"account.wishlist.index","text":[],"link":""},{"type":"static","value":"brands.index","text":{"en":"","zh_cn":""},"link":""}]},"link3":{"title":{"en":"Other","zh_cn":"\u5176\u4ed6"},"links":[{"type":"static","value":"brands.index","text":[],"link":""},{"type":"static","value":"account.index","text":{"en":"","zh_cn":""},"link":""},{"type":"page","value":20,"text":{"en":"","zh_cn":""},"link":""},{"type":"page","value":21,"text":{"en":"","zh_cn":""},"link":""}]},"contact":{"telephone":"028-87966209","address":{"en":"G8 Tianfu Software Park Chengdu China, Guangda Network Technology Co., Ltd.","zh_cn":"\u6210\u90fd\u5e02\u9ad8\u65b0\u533a\u76ca\u5dde\u5927\u9053\u4e2d\u6bb51858\u53f7\u5929\u5e9c\u8f6f\u4ef6\u56edG8 \u6210\u90fd\u5149\u5927\u7f51\u7edc\u79d1\u6280\u6709\u9650\u516c\u53f8"},"email":"Marketing@guangda.work"}},"bottom":{"copyright":{"en":"<div>Technical Support <a href=\"https:\/\/beikeshop.com\/\" target=\"_blank\" rel=\"noopener\">beikeshop.com<\/a>&nbsp; - Chengdu Guangda Network Technology &copy; 2022<\/div>","zh_cn":"<div class=\"\">\u6280\u672f\u652f\u6301 <a href=\"https:\/\/beikeshop.com\/\" target=\"_blank\" rel=\"noopener\">beikeshop.com<\/a>&nbsp; - \u6210\u90fd\u5149\u5927\u7f51\u7edc\u79d1\u6280 &copy; 2022<\/div>"},"image":"catalog\/demo\/banner\/pay_icons.png"}}';
        $setting = json_decode($json, true);
        if (empty($setting)) {
            throw new \Exception("无效的底部配置数据");
        }
        return json_encode($setting);
    }
}
