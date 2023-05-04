<?php
namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\By;
use function PHPUnit\Framework\assertNotEquals;

require_once(dirname(__FILE__) . '/../../data/catalog/login.php');
require_once(dirname(__FILE__) . '/../../data/catalog/login_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/account_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/product_1.php');
require_once(dirname(__FILE__) . '/../../data/catalog/index_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/checkout_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/order_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/order_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/express.php');
require_once(dirname(__FILE__) . '/../../data/admin/cre_product.php');
require_once(dirname(__FILE__) . '/../../data/admin/product_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/cre_product_page.php');


////库存不足时下单
class UnderstockOrderTest extends DuskTestCase
{
    public function testUnderstockOrder()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
            //登录后台
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
            //修改商品库存为5
                ->click(admin_top['mg_product']);
            //获取商品名
                 $product1_text=$browser->text(products_top['get_name']);
                 echo $product1_text;
                //点击编辑商品
                $browser->press(products_top['edit_product'])
                //修改商品库存为5
                ->type(product_top['quantity'], alter_product['low_quantity'])
                //5.点击保存
                ->press(product_top['save_btn'])
                ->pause(3000)

            //去往前台下单
                ->clickLink($product1_text)
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
                //输入库存
                $browser->pause(2000)
                ->type(product['quantity'], alter_product['low_quantity'])
                //在库存基础上数量+1  quantity_up
                ->click(product['quantity_up'])
                //4.点击购买按钮
                ->press(product['product_1'])
                ->pause(2000)
                //断言 understock_assert
                ->assertVisible(product['understock_assert'])
                    ;



        });
    }
}
