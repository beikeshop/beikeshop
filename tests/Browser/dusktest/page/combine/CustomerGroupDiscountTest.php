<?php
namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Laravel\Dusk\TestCase;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\By;
use function PHPUnit\Framework\assertEquals;
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
require_once(dirname(__FILE__) . '/../../data/admin/cus_grounp.php');
require_once(dirname(__FILE__) . '/../../data/admin/customer_page.php');

///客户组折扣判断
class CustomerGroupDiscountTest extends DuskTestCase
{
    public function testCustomerGroupDiscount()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
            //1.后台登录，设置客户组折扣为30
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //2.点击客户管理
                ->click(admin_top['mg_customers'])
                ->pause(3000)
                //4.点击客户组
                ->click(customer_left['customer_group'])
                //5.点击编辑客户组
                ->press(customer_group['edit_cus_group'])
                //4.填写客户组折扣为30
                ->type(cre_cus_group['discount'], alter_cus_group_info['discount'])
                //5.点击保存
                ->press(cre_cus_group['save_btn'])
                ->pause(5000)
                ->assertSee(alter_cus_group_info['ch_group_name']);
            //前台用户登录
                //点击登录图标
                $browser->click(admin_top['root'])
                ->pause(3000)
                ->click(admin_top['go_catalog'])
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
                $browser->click(index_login['login_icon'])
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(5000)
                ->click(account['go_index'])
                //3.向下滑动页面直到找到商品
                ->pause(2000)
                ->scrollIntoView(index['product_img'])
                ->pause(2000)
                //点击要购买的商品
                ->press(index['product_img'])
                //4.点击购买按钮
                ->press(product['product_1'])
                ->pause(5000);
            //获取购买商品价格
                $old_product_price = $browser->element(checkout['product_price']);
                $text = $old_product_price->getText();
                $matches = [];
                preg_match('/[\d\.]+/', $text, $matches);
                $new_product_price= $matches[0];

            // 获取购买商品的数量
                $old_quantity= $browser->element(checkout['quantity']);
                $text = $old_quantity->getText();
                $matches = [];
                preg_match('/\d+/', $text, $matches);
                $new_quantity = $matches[0];
            //商品总价
//                $old_product_total = $browser->element(checkout['product_total']);
//                $text = $old_product_total->getText();
//                $matches = [];
//                preg_match('/[\d\.]+/', $text, $matches);
//                $new_product_total = $matches[0];
            //运费
                $old_shipping_fee = $browser->element(checkout['shipping_fee']);
                $text = $old_shipping_fee->getText();
                $matches = [];
                preg_match('/[\d\.]+/', $text, $matches);
                $new_shipping_fee = $matches[0];
            //折扣金额
//                $old_customer_discount = $browser->element(checkout['customer_discount']);
//                $text = $old_customer_discount->getText();
//                $matches = [];
//                preg_match('/[\d\.]+/', $text, $matches);
//                $new_customer_discount = $matches[0];
//            //实际金额
//                $old_order_total = $browser->element(checkout['order_total']);
//                $text = $old_order_total->getText();
//                $matches = [];
//                preg_match('/[\d\.]+/', $text, $matches);
//                $new_order_total = $matches[0];
                //打印订单号
//                echo $new_product_price;
//                echo $new_quantity;
//                echo $new_product_total;
//                echo $new_shipping_fee;
//                echo $new_customer_discount;
//                echo $new_order_total;
                $discunt_price=$new_product_price*$new_quantity*(30/100);
//                 echo $discunt_price;
                $true_price=$new_product_price*$new_quantity-$discunt_price+$new_shipping_fee;
                $browser->assertSeeIn(checkout['customer_discount'],$discunt_price,)
                ->assertSeeIn(checkout['order_total'],$true_price);
                //5.点击确认按钮


                    ;



        });
    }
}
