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
class AddressTest extends DuskTestCase
{
    public function testAddress()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                //1.用户登录
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(5000)
                //2.点击address
                ->click(account['go_address'])
                //3.点击添加地址
                ->press(address['add_btn'])
                ->pause(3000)
                //3.1  name
                ->type(address['add_name'], add_address['add_name'])
                //3.2 phone
                ->type(address['add_phone'], add_address['add_phone'])
                //3.3 address
                ->type(address['add_address'], add_address['add_name'])
                //3.4 code
                ->type(address['add_code'], add_address['add_code'])
                //3.5 address1
                ->type(address['add_address1'], add_address['add_address2'])
                //3.6 address2
                ->type(address['add_address2'], add_address['add_address2'])
                //3.7 defaule
                ->press((address['default']))
                //3.8 save
                ->press((address['save']))
                ->pause(3000)
                ->assertSee(address['assert']);
        });
    }
}
