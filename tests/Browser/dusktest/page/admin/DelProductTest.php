<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/index_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/product_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/cre_product_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/cre_product.php');
class DelProductTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */



//场景1 email不合法

   public function testEditProduct()
   {

       $this->browse(function (Browser $browser)
       {
           $browser->visit(admin_login['login_url'])
               //1.登录
               ->type(admin_login['login_email'], admin_true_login['email'])
               ->type(admin_login['login_pwd'], admin_true_login['password'])
               ->press(admin_login['login_btn'])
               ->pause(2000)
               ->clickLink(admin_top['mg_product']);

               $product1_text=$browser->text(products_top['get_name']);
               echo $product1_text;
               //2.点击商品管理

               //3.点击添加商品
               $browser->press(products_top['del_product'])
               //确认
               ->press(products_top['sure_btn']);

               $browser->pause(2000)
               ->assertDontSee($product1_text)
               ->pause(5000)

           ;
       });
   }
}
