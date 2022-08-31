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
use Illuminate\Support\Facades\Redirect;

class WelcomeController extends Controller
{
    private $languages = [
        'zh_cn' => '简体中文',
        'en' => 'English',
    ];


    public function index()
    {
        if (installed()) {
            exit('Already installed');
        }

        $data['languages'] = $this->languages;
        $data['locale'] = $_COOKIE['locale'] ?? 'en';
        $data['steps'] = 1;

        return view('installer::welcome', $data);
    }

    public function locale($lang)
    {
        if (in_array($lang, languages()->toArray())) {
            setcookie('locale', $lang, 0, '/');
        }
        return Redirect::back();
    }
}
