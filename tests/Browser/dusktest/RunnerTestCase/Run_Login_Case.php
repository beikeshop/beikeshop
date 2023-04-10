<?php


namespace Tests\Browser;
namespace App\Http\Controllers;
namespace  App\Http\Controllers\login;
namespace App\Http\Controllers\TestCase;
use App\Http\Controllers\LoginGrounp;



use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\By;
use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';
require_once(dirname(__FILE__) . '/../page/front/loginGrounp.php');


class Run_Login_Case extends DuskTestCase
{

    /**
     * A basic browser test example.
     */

    public function runScenarios(array $scenarios)
    {
        foreach ($scenarios as $scenario) {
            $this->$scenario();
        }
    }

    public function test_login_run()
    {
        $Loginful = new LoginGrounp();
        $Loginful->openurl();

//      $this->runScenarios([
//        'openurl',
//        'ture_email',
//        'ture_password',
//        'click_login_btn',
//        'ture_assert',
//    ]);
    }
}
