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

        $languageDir = base_path('beike/Installer/Lang');
        $packages = array_values(array_diff(scandir($languageDir), array('..', '.')));
        $Languages = collect($packages)->filter(function ($package) {
            return file_exists(base_path("beike/Installer/Lang/{$package}"));
        })->toArray();
        $data['languages'] = array_values($Languages);
        $data['steps'] = 1;

        return view('installer::welcome', $data);
    }

    public function locale($lang)
    {
        $languageDir = base_path('beike/Installer/Lang');
        $packages = array_values(array_diff(scandir($languageDir), array('..', '.')));
        if (in_array($lang, $packages)) {
            Session::put('locale', $lang);
        }
        return Redirect::back();
    }
}
