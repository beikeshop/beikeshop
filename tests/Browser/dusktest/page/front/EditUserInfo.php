<?php

namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


require_once(dirname(__FILE__) . '/../../data/catalog/login.php');
require_once(dirname(__FILE__) . '/../../data/catalog/login_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/account_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/account.php');



//已注册客户且有地址，直接购买商品
class EditUserInfo extends DuskTestCase
{
    public function testEditInfo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                //1.用户登录
                ->type(login['login_email'], true_register['email'])
                ->type(login['login_pwd'], true_register['password'])
                ->press(login['login_btn'])
                ->pause(2000)
                //2.点击编辑
                ->clickLink(Edit['go_Edit'])
                ->pause(1000)
                //3.点击上传头像按钮
//                ->press(Edit['upload_btn'])
//                ->pause(3000)
//                ->waitFor('#file-input') // 等待文件上传控件出现
//                ->assertVisible('#file-input') // 确保文件上传控件可见
//                ->assertEnabled('#file-input') // 确保文件上传控件可用
//                ->attach(Edit['upload_btn'],realpath('E:/phpstudy_pro/WWW/autotest.test/beikeshop/tests/Browser/dusktest/data/images/Headpicture/Headpicture.jpeg'))
//                ->press(Edit['Confirm_btn'])
//                ->pause(3000)
                //3.1  name
                ->type(Edit['user_name'], user_edit['user_name'])
                //3.2 phone
                ->type(Edit['user_email'], user_edit['user_email'])
                //3.3 save
                ->press((Edit['Submit']))
                ->pause(3000)
                ->assertSee(Edit['assert']);
                //3.向下滑动页面直到找到元素

        });
    }
}
