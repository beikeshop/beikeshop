<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-06-06 17:17:04
 * @modified   2023-06-06 17:17:04
 */

namespace Tests\Browser\Pages\Combine;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\CusGrounp;
use Tests\Data\Admin\CustomerPage;
use Tests\Data\Admin\LoginData;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\CataLoginData;
use Tests\Data\Catalog\CheckoutPage;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

///客户组折扣判断
class CustomerGroupDiscountTest extends DuskTestCase
{
    public function testCustomerGroupDiscount()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //2.点击客户管理
                ->click(AdminPage::TOP['mg_customers'])
                ->pause(3000)
                //4.点击客户组
                ->click(CustomerPage::Left['customer_group'])
                //5.点击编辑客户组
                ->press(CustomerPage::Customer_Group['edit_cus_group'])
                //4.填写客户组折扣为30
                ->type(CustomerPage::Create_Group['discount'], CusGrounp::Alter_Group_Info['discount'])
                //5.点击保存
                ->press(CustomerPage::Create_Group['save_btn'])
                ->pause(5000);
            //前台用户登录
            //点击登录图标
            $browser->click(AdminPage::TOP['root'])
                ->pause(3000)
                ->click(AdminPage::TOP['go_catalog'])
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $browser->click(IndexPage::Index_Login['login_icon'])
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                ->click(AccountPage::Account['go_index'])
            //3.向下滑动页面直到找到商品
                ->pause(2000)
                ->scrollIntoView(IndexPage::Index['product_img'])
                ->pause(2000)
            //点击要购买的商品
                ->press(IndexPage::Index['product_img'])
            //4.点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(5000);
            //获取购买商品价格
            $old_product_price = $browser->element(CheckoutPage::Checkout['product_price']);
            $text              = $old_product_price->getText();
            $matches           = [];
            preg_match('/[\d\.]+/', $text, $matches);
            $new_product_price = $matches[0];

            // 获取购买商品的数量
            $old_quantity = $browser->element(CheckoutPage::Checkout['quantity']);
            $text         = $old_quantity->getText();
            $matches      = [];
            preg_match('/\d+/', $text, $matches);
            $new_quantity = $matches[0];
            //商品总价
            //                $old_product_total = $browser->element(CheckoutPage::Checkout['product_total']);
            //                $text = $old_product_total->getText();
            //                $matches = [];
            //                preg_match('/[\d\.]+/', $text, $matches);
            //                $new_product_total = $matches[0];
            //运费
            $old_shipping_fee = $browser->element(CheckoutPage::Checkout['shipping_fee']);
            $text             = $old_shipping_fee->getText();
            $matches          = [];
            preg_match('/[\d\.]+/', $text, $matches);
            $new_shipping_fee = $matches[0];
            //折扣金额
            //                $old_customer_discount = $browser->element(CheckoutPage::Checkout['customer_discount']);
            //                $text = $old_customer_discount->getText();
            //                $matches = [];
            //                preg_match('/[\d\.]+/', $text, $matches);
            //                $new_customer_discount = $matches[0];
            //            //实际金额
            //                $old_order_total = $browser->element(CheckoutPage::Checkout['order_total']);
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
            $discunt_price = $new_product_price * $new_quantity * (30 / 100);
            //                 echo $discunt_price;
            $true_price = $new_product_price * $new_quantity - $discunt_price + $new_shipping_fee;
            $browser->assertSeeIn(CheckoutPage::Checkout['customer_discount'], $discunt_price)
                ->assertSeeIn(CheckoutPage::Checkout['order_total'], $true_price);
            //5.点击确认按钮

        });
    }
}
