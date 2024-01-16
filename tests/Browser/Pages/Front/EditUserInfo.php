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

namespace Tests\Browser\Pages\Front;

use Laravel\Dusk\Browser;
use Tests\Data\Catalog\AccountData;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\RegisterData;
use Tests\DuskTestCase;

//已注册客户且有地址，直接购买商品
class EditUserInfo extends DuskTestCase
{
    public function testEditInfo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //1.用户登录
                ->type(LoginPage::Login['login_email'], RegisterData::True_Register['email'])
                ->type(LoginPage::Login['login_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(2000)
                //2.点击编辑
                ->click(AccountPage::Account['go_Edit'])
                ->pause(1000)
                //3.点击上传头像按钮
//                ->press(AccountPage::Edit['upload_btn'])
//                ->pause(3000)
//                ->waitFor('#file-input') // 等待文件上传控件出现
//                ->assertVisible('#file-input') // 确保文件上传控件可见
//                ->assertEnabled('#file-input') // 确保文件上传控件可用
//                ->attach(AccountPage::Edit['upload_btn'],realpath('.tests/Browser/dusktest/data/Images/Headpicture/Headpicture.jpeg'))
//                ->press(AccountPage::Edit['Confirm_btn'])
//                ->pause(3000)
                //3.1  name
                ->type(AccountPage::Edit['user_name'], AccountData::User_Edit['user_name'])
                //3.2 phone
                ->type(AccountPage::Edit['user_email'], AccountData::User_Edit['user_email'])
                //3.3 save
                ->press((AccountPage::Edit['Submit']))
                ->pause(3000)
                ->assertSee(AccountPage::Edit['assert']);
            //3.向下滑动页面直到找到元素

        });
    }
}
