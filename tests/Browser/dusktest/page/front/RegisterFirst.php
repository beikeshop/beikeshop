<?php



namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\By;

require_once(dirname(__FILE__) . '/../../data/catalog/login.php');
require_once(dirname(__FILE__) . '/../../data/catalog/login_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/register.php');
require_once(dirname(__FILE__) . '/../../data/catalog/account_page.php');


class RegisterFirst extends DuskTestCase
{
    /**
     * A basic browser test example.
     */


    #1.先单独注册一个账号
    public function testLoginFirst()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], false_register['exist_email'])
                ->type(register['register_pwd'], true_register['password'])
                ->type(register['register_re_pwd'], true_register['password'])
                ->pause(2000)
                ->press(register['register_btn'])
                ->pause(6000)
                ->assertPathIs(account['url'])
                ->pause(2000)
            ;
        });
    }
}
