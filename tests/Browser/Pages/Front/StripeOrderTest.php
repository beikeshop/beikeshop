<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-07-26 10:10:04
 * @modified   2023-07-26 10:10:04
 */

namespace Tests\Browser\Pages\Front;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
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

//已注册客户且有地址，在下单时更换stripe支付方式购买
class StripeOrderTest extends DuskTestCase
{
    public function testStripeOrder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //1.用户登录
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                //当前网址断言
                ->assertPathIs(AccountPage::Account['url'])
                //2.点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
                //3.向下滑动页面直到找到元素
                ->scrollIntoView(IndexPage::Index['product_img'])
                ->pause(2000)
                //点击要购买的商品
                ->press(IndexPage::Index['product_img'])
                //4.点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(5000)
                //点击第二种支付方式

                ->elements(CheckoutPage::Checkout['method_pay'])[1]->click();
            $browser->pause(5000)
            //5.点击确认按钮
                ->press(CheckoutPage::Checkout['submit'])
                ->pause(5000)
            //填写卡号信息
                ->type(OrderPage::Stripe_Plugin['Cardholder_Name'], PaymentData::Payment_Stripe['Cardholder_Name']);
            //切换窗口
            //填写卡号
            $wait          = new WebDriverWait($browser->driver, 10);
            $iframeElement = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('#card-number-element > div > iframe')
            ));
            $browser->driver->switchTo()->frame($iframeElement);
            $browser->type(OrderPage::Stripe_Plugin['Card_Number'], PaymentData::Payment_Stripe['Card_Number'])
                ->driver->switchTo()->defaultContent();

            //填写过期时间
            $wait          = new WebDriverWait($browser->driver, 10);
            $iframeElement = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('#card-expiry-element > div > iframe')
            ));
            $browser->driver->switchTo()->frame($iframeElement);
            $browser->pause(5000)
                ->type(OrderPage::Stripe_Plugin['Expiration_Date'], PaymentData::Payment_Stripe['Expiration_Date'])
                ->driver->switchTo()->defaultContent();

            // 填写cvv
            $wait          = new WebDriverWait($browser->driver, 10);
            $iframeElement = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('#card-cvc-element > div > iframe')
            ));
            $browser->driver->switchTo()->frame($iframeElement)
                ->wait(OrderPage::Stripe_Plugin['Card_Number']);
            $browser->type(OrderPage::Stripe_Plugin['CVV'], PaymentData::Payment_Stripe['CVV'])
                ->driver->switchTo()->defaultContent();
            $browser->press(OrderPage::Stripe_Plugin['Submit_Btn'])
                ->pause(5000)
            //6.断言
                ->assertSee(OrderPage::Stripe_Plugin['Assert_Test']);
        });
    }
}
