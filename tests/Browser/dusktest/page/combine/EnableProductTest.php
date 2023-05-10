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
require_once dirname(__FILE__) . '/../../data/catalog/product_1.php';
class EnableProductTest extends DuskTestCase
{
   /**
    * A basic browser test example.
    * @return void
    */

//启用商品

   public function testEnableProduct()
   {

       $this->browse(function (Browser $browser) {
           $browser->visit(admin_login['login_url'])
               //登录
               ->type(admin_login['login_email'], admin_true_login['email'])
               ->type(admin_login['login_pwd'], admin_true_login['password'])
               ->press(admin_login['login_btn'])
               ->pause(2000)
               ->click(admin_top['mg_product']);
               $product1_text = $browser->text(products_top['get_name']);
               echo $product1_text;
               //编辑商品
               $browser->press(products_top['edit_product'])
               //启用商品
               ->click(product_top['Enable'])
               //点击保存
               ->press(product_top['save_btn'])
               ->pause(3000)
               //点击商品，跳转前台
               ->clickLink($product1_text)
               ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
               //断言页面是否有购买按钮
               $browser->assertVisible(product['product_1'])
               ->pause(3000);
       });
   }
}
