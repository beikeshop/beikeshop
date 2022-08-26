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
use Beike\Repositories\LanguageRepo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class WelcomeController extends Controller
{
    public function index()
    {
        if (installed()) {
            exit('Already installed');
        }

        $data['languages'] = LanguageRepo::enabled();

        return view('installer::welcome', $data);
    }

    public function locale($lang)
    {
        if (in_array($lang, languages()->toArray())) {
            Session::put('locale', $lang);
        }
        return Redirect::back();
    }
}
