<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-10-11 11:17:04
 * @modified   2023-10-11 11:17:04
 */

namespace Tests\Browser\Pages\Front;

use Laravel\Dusk\Browser;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\CataLoginData;
use Tests\Data\Catalog\CategoriesPage;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\DuskTestCase;

//商品价格倒序排列
class DescPriceTest extends DuskTestCase
{
    public function testAscName()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //1.用户登录
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                //2.点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
                //3.点击导航栏sports
                ->click(IndexPage::Index['top_Sports'])
                ->click(CategoriesPage::Categories['sort_button'])
                ->click(CategoriesPage::Categories['desc_price']);
            $product       = $browser->elements(CategoriesPage::Categories['product_price']);
            $product_1     = $product[1]->getText(); //获取第一个商品价格AscPriceTest.php
            $first_letter1 = substr($product_1, 1);
            $product_2     = $product[2]->getText(); //获取第二个商品价格
            $first_letter2 = substr($product_2, 1);
            $product_3     = $product[3]->getText(); //获取第三个商品价格
            $first_letter3 = substr($product_3, 1);
            //打印订单号
            echo $first_letter1;
            echo $first_letter2;
            echo $first_letter3;
            // 断言函数：判断三个变量是否正序排列
            assert(ord($first_letter1) >= ord($first_letter2) && ord($first_letter2) >= ord($first_letter3), '变量未倒序排列');
            $browser->pause(10000);

        });
    }
}
