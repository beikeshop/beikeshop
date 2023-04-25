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
require_once(dirname(__FILE__) . '/../../data/admin/systemset_page.php');

//禁止游客结账
class CloseVisiterCheckoutTest extends DuskTestCase
{
    public function testCancelOrder()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
        //登录后台
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //点击系统设置
                ->click(admin_top['system_set'])
                //点击结账设置
                ->click(system_set['pay_set'])
                ->pause(2000)
                //点击结游客结账 禁用
                ->press(system_set['close_visitor_checkout'])
                //点击保存
                ->press(common['save_btn'])
                ->pause(2000)
        //去往前台验证
                ->click(admin_top['root'])
                ->pause(3000)
                ->click(admin_top['go_catalog'])
                ->pause(2000)
                //切换到前台下单
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            //前台用户登录
                $browser->pause(2000)
                //向下滑动页面直到找到商品
                ->scrollIntoView(index['product_img'])
                ->pause(2000)
                //点击要购买的商品
                ->press(index['product_img'])
                //点击购买按钮
                ->press(product['product_1'])
                ->pause(5000)
        //断言：出现登录窗体则通过
                ->assertVisible(iframe['iframe_name'])


                    ;



        });
    }
}
