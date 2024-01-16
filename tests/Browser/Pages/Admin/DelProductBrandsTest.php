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

class DelProductBrandsTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testEditProductBrands()
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
                //3.点击编辑按钮
                ->press(ProductPage::Cre_brand['del_brand_btn'])
                ->press(ProductPage::Cre_brand['del_sure_btn'])
                ->pause(3000)
                ->assertDontSee(CreBrandsData::Alter_Brands_Info['alter_brand_name']);
        });
    }
}
