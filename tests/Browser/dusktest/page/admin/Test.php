<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\By;

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


class Test extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testAddCusGroup()
    {

        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(5000)
                ->assertSee(true_login['assert'])
                ->pause(5000)
                ->clickLink(account['go_index'])
                //3.向下滑动页面直到找到商品
                ->pause(2000)
                ->scrollIntoView(index['product_img'])
                ->pause(2000)
                //点击要购买的商品
                ->press(index['product_img'])
                //4.点击购买按钮
                ->press(product['product_1'])
                ->pause(5000)
                //5.点击确认按钮
                ->press(checkout['submit'])
                ->pause(5000);
                $elements = $browser->elements(checkout['order_num']);
                $order_num = $elements[15]->getText();
                //打印订单号
                echo $order_num;
                $browser->clickLink(checkout['view_order'])
                ->pause(4000);
//                $text = $browser->text(get_order_status['status_text']);
                $browser->assertSeeIn(get_order_status['status_text'],'Unpaid');
//                echo $text
            ;
        });
    }
}
