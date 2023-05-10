<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once dirname(__FILE__) . '/../../data/admin/login.php';
require_once dirname(__FILE__) . '/../../data/admin/login_page.php';
require_once dirname(__FILE__) . '/../../data/admin/admin_page.php';
require_once dirname(__FILE__) . '/../../data/catalog/index_page.php';
require_once dirname(__FILE__) . '/../../data/admin/product_page.php';
require_once dirname(__FILE__) . '/../../data/admin/cre_product_page.php';
require_once dirname(__FILE__) . '/../../data/admin/cre_product.php';
class EditProductTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */

//场景1 email不合法
    public function testEditProduct()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(admin_login['login_url'])
                //1.登录
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //2.点击商品管理
                ->click(admin_top['mg_product'])
                //3.点击编辑商品
                ->press(products_top['edit_product'])
                //4.填写商品信息
                ->type(product_top['ch_name'], alter_product['ch_name'])
                ->type(product_top['en_name'], alter_product['en_name'])
                ->type(product_top['sku'], alter_product['sku'])
                ->type(product_top['price'], alter_product['price'])
                ->type(product_top['origin_price'], alter_product['origin_price'])
                ->type(product_top['cost_price'], alter_product['cost_price'])
                ->type(product_top['quantity'], alter_product['quantity'])
                //5.点击保存
                ->press(product_top['save_btn'])
                ->pause(3000)
                ->assertSee(cre_assert['alter_ful_assert']);
                });
    }
}
