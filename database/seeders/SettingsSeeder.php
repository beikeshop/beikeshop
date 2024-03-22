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
            ["type" => "system", "space" => "base", "name" => "theme", "value" => "default", "json" => 0],
            ["type" => "plugin", "space" => "service_charge", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "status", "value" => "", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "admin_name", "value" => "admin", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "tax", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "tax_address", "value" => "payment", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "currency", "value" => "USD", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "zone_id", "value" => "710", "json" => 0],
            ["type" => "plugin", "space" => "header_menu", "name" => "status", "value" => "1", "json" => 0],
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
            ["type" => "system", "space" => "base", "name" => "meta_title", "value" => "开源好用的跨境电商系统", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "meta_description", "value" => "BeikeShop 是一款开源好用的跨境电商建站系统，基于 Laravel 开发。主要面向外贸，和跨境行业。系统提供商品管理、订单管理、会员管理、支付、物流、系统管理等丰富功能", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "meta_keywords", "value" => "开源电商,开源代码,开源电商项目,b2b独立站,dtc独立站,跨境电商网", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "telephone", "value" => "028-88888888", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "email", "value" => "support@example.com", "json" => 0],
            ["type" => "plugin", "space" => "flat_shipping", "name" => "type", "value" => "percent", "json" => 0],
            ["type" => "plugin", "space" => "flat_shipping", "name" => "value", "value" => "10", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "default_customer_group_id", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "stripe", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "latest_products", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "flat_shipping", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "plugin", "space" => "paypal", "name" => "status", "value" => "1", "json" => 0],
            ["type" => "system", "space" => "base", "name" => "rate_api_key", "value" => "", "json" => 0],
        ];
    }
}
