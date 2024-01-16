<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-07-04 15:01:04
 * @modified   2023-07-04 15:01:04
 */

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\CreBrandsData;
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\ProductPage;
use Tests\DuskTestCase;

class AddProductBrandsTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */

    //场景1 email不合法
    public function testAddProductBrands()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //2.点击商品
                ->click(AdminPage::TOP['mg_product'])
                //2.点击商品品牌
                ->click(ProductPage::Product_Left['product_brand'])
                //3.点击创建
                ->press(ProductPage::Cre_brand['cre_brand_btn'])
                //4.填写商品品牌信息
                ->type(ProductPage::Cre_brand['brand_name'], CreBrandsData::Brands_Info['brand_name'])
                //从图片管理器选择图片
                ->press(ProductPage::Cre_brand['brand_img'])
                ->pause(6000)
                //切换窗体
                ->withinFrame('#layui-layer-iframe1', function ($brower) {
                    $brower->click(ProductPage::Mg_Images['first_img'])
                        ->press(ProductPage::Mg_Images['choose_btn']);
                })
                ->driver->switchTo()->defaultContent();
            $browser->pause(2000)
                ->type(ProductPage::Cre_brand['brand_first_letter'], CreBrandsData::Brands_Info['brand_first_letter'])
            //5.点击保存
                ->press(ProductPage::Cre_brand['save_btn'])
                ->pause(3000)
                ->assertSee(CreBrandsData::Brands_Info['brand_name']);
        });
    }
}
