<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-07-20 17:17:04
 * @modified   2023-07-25 10:10:04
 */

namespace Tests\Browser\Pages\Front;

use Laravel\Dusk\Browser;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\CataLoginData;
use Tests\Data\Catalog\CheckoutPage;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\OrderPage;
use Tests\Data\Catalog\PaymentData;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

//paypal沙盒支付 该页面加载时间较长，失败率较高
class PaypalOrderTest extends DuskTestCase
{
    public function testPaypalOrder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //用户登录
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                //当前网址断言
                ->assertPathIs(AccountPage::Account['url'])
                //点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
                //向下滑动页面直到找到元素
                ->scrollIntoView(IndexPage::Index['product_img'])
                ->pause(2000)
                //点击要购买的商品
                ->press(IndexPage::Index['product_img'])
                //点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(3000)
                //点击提交按钮
                ->press(CheckoutPage::Checkout['submit'])
                ->pause(5000)
                ->waitFor(OrderPage::Paypal_Plugin['Paypal_iframe'], 10)
                //点击paypal支付按钮
                ->click(OrderPage::Paypal_Plugin['Paypal_iframe']);
            //定位到弹出的paypal窗体
            $popupWindowHandle = null;
            $mainWindowHandle  = $browser->driver->getWindowHandle();

            // 获取所有窗口句柄
            foreach ($browser->driver->getWindowHandles() as $windowHandle) {
                // 切换到每个窗口
                $browser->driver->switchTo()->window($windowHandle);

                // 判断当前窗口是否为弹窗窗口，可以根据标题、URL 或其他标识进行判断
                if (strpos($browser->driver->getTitle(), 'PayPal') !== false) {
                    $popupWindowHandle = $windowHandle;

                    break;
                }
            }

            // 切换回主窗口
            $browser->driver->switchTo()->window($mainWindowHandle);

            // 在弹窗中进行操作
            if ($popupWindowHandle) {
                $browser->driver->switchTo()->window($popupWindowHandle);
                $currentUrl = $browser->driver->getCurrentURL();
                echo $currentUrl;
                $browser->waitFor(OrderPage::Paypal_Plugin['Paypal_foot'], 30000) // 等待目标元素加载并可见
                    ->scrollIntoView(OrderPage::Paypal_Plugin['Paypal_foot'])
                    ->pause(1000) // 等待页面滚动完成
                    ->clickLink(OrderPage::Paypal_Plugin['Paypal_Login'])//点击登录
                    ->type(OrderPage::Paypal_Plugin['Paypal_Email'], PaymentData::Payment_Paypal['Paypal_Email'])//输入账号
                    ->press(OrderPage::Paypal_Plugin['Next_Btn'])    //点击下一步
                    ->pause(5000)
                    ->type(OrderPage::Paypal_Plugin['Paypal_Pwd'], PaymentData::Payment_Paypal['Paypal_Pwd'])//输入密码
                    ->press(OrderPage::Paypal_Plugin['Paypal_Login'])//点击登录
                    ->pause(5000)
                    ->click(OrderPage::Paypal_Plugin['Payment_Method'])//选择支付方式
                    ->press(OrderPage::Paypal_Plugin['Submit_Btn'])//点击完成购物
                    ->pause(5000);
            }

            // 切换回主窗口
            $browser->driver->switchTo()->window($mainWindowHandle);
            $browser->pause(5000)
                ->assertSeeIn(OrderPage::Get_Order_Status['status_text'], OrderPage::Order_Status['Paid']);

        });
    }
}
