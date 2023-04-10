<?php


namespace Tests\Browser;

namespace App\Http\Controllers;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once 'E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\data\login_page.php';
require_once 'E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\data\register.php';
class RegisterTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testRegisterTest(): void

    {
//        $user = User::factory()->create([
//            'email' => 'test@163.com',
//        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->pause(2000)
                ->type(register['register_email'], ture['email'])
                ->type(register['register_pwd'], ture['password'])
                ->type(register['register_re_pwd'], ture['re_password'])
                ->press(register['register_btn'])
                ->assertSee(register['register_text'])
                ->pause(3000);
        });
    }
}
