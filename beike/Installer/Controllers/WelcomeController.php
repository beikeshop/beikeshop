<?php
/**
 * WelcomeController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-12 20:17:04
 * @modified   2022-08-12 20:17:04
 */

namespace Beike\Installer\Controllers;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        if (installed()) {
            exit('Already installed');
        }

        $steps = 1;

        return view('installer::welcome', compact('steps'));
    }
}
