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

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminOrderPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\Express;
use Tests\Data\Admin\LoginData;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\CataLoginData;
use Tests\Data\Catalog\CheckoutPage;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\OrderPage;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

//已注册客户且有地址，直接购买商品
class AlterOrderStationTest extends DuskTestCase
{
    public function testAlterOrderStation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //去往前台
                ->click(AdminPage::TOP['root'])
                ->pause(3000)
                ->click(AdminPage::TOP['go_catalog'])
                ->pause(2000)
                //切换到前台下单
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            //前台用户登录
            //点击登录图标
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
                ->pause(3000)
            //5.点击确认按钮
                ->press(CheckoutPage::Checkout['submit'])
                ->pause(5000);
            $elements  = $browser->elements(CheckoutPage::Checkout['order_num']);
            $order_num = $elements[16]->getText();
            //打印订单号
            echo $order_num;
            //点击个人中心按钮
            $browser->click(IndexPage::Index_Login['login_icon'])
                ->click(AccountPage::Account['go_order'])
                ->click(AccountPage::Order['check_btn'])
                ->pause(3000)

//                $browser->click(CheckoutPage::Checkout['view_order'])
            //进入后台,修改订单状态为已支付
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[0]);
            //点击订单管理按钮
            $browser->click(AdminPage::TOP['mg_order'])
                ->pause(3000)
            //搜索框输入刚下单的订单号
                ->type(AdminOrderPage::Right['search_order'], $order_num)
            //点击搜索按钮
                ->press(AdminOrderPage::Right['search_bth'])
                ->assertSee($order_num)
            //点击查看按钮
                ->press(AdminOrderPage::Right['view_btn'])
            //点击状态栏下拉按钮
                ->pause(2000)
                ->press(AdminOrderPage::Details['pull_btn'])
            //修改状态为已支付
                ->pause(2000)
                ->click(AdminOrderPage::Details['paid'])
                ->press(AdminOrderPage::Details['alter_btn'])
                ->pause(3000)
            //切换到前台
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $browser->pause(3000)
            //刷新页面
                ->refresh()
                ->pause(1000)
            // 断言是否已支付
                ->assertSeeIn(OrderPage::Get_Order_Status['status_text'], OrderPage::Order_Status['Paid'])
            //切换到后台，将状态改为已发货
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[0]);
            $browser->pause(2000)
                ->press(AdminOrderPage::Details['pull_btn'])
            //修改状态为发货
                ->pause(2000)
                ->click(AdminOrderPage::Details['Shipped'])
                ->press(AdminOrderPage::Details['express_btn'])
            //选择快递并填写订单号
                ->pause(2000);
            // 找到所有 class 为 el-scrollbar__view el-select-dropdown__list 的元素
            $elements = $browser->elements(AdminOrderPage::Details['express_1']);
            // 获取第二个元素
            $secondElement = $elements[1];
            // 找到第一个子元素并点击它
            $secondElement->findElement(WebDriverBy::xpath('./*[1]'))->click();
            $browser->type(AdminOrderPage::Details['order_number'], Express::Express['express_code'])
                ->pause(2000)
            //向下滑动找到更新按钮
                ->scrollIntoView(AdminOrderPage::Details['alter_btn'])
                ->pause(2000)
            //按下更新按钮
                ->press(AdminOrderPage::Details['alter_btn'])
                ->pause(3000)
            //切换到前台,断言是否已发货
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $browser->pause(3000)
                ->refresh()
                ->pause(4000)
                ->assertSeeIn(OrderPage::Get_Order_Status['status_text'], OrderPage::Order_Status['Shipped'])
            //切换到后台，修改状态为已完成
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[0]);
            $browser->pause(2000)
                ->press(AdminOrderPage::Details['pull_btn'])
            //修改状态为已完成
                ->pause(2000)
                ->press(AdminOrderPage::Details['Completed'])
            //按下更新按钮
                ->press(AdminOrderPage::Details['alter_btn'])
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $browser->pause(3000)
                ->refresh()
                ->pause(4000)
                ->assertSeeIn(OrderPage::Get_Order_Status['status_text'], OrderPage::Order_Status['Completed']);

        });
    }
}
