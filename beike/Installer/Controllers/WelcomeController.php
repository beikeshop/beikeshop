<?php
/**
 * WelcomeController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-12 20:17:04
 * @modified   2022-08-12 20:17:04
 */

namespace Beike\Installer\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class WelcomeController extends BaseController
{
    private $languages = [
        'zh_cn' => '简体中文',
        'en'    => 'English',
    ];

    public function index()
    {
        $this->checkInstalled();
        $data['languages'] = $this->languages;
        $data['locale']    = $_COOKIE['locale'] ?? 'zh_cn';
        $data['steps']     = 1;

        return view('installer::welcome', $data);
    }

    public function locale(Request $request)
    {
        $lang = $request->get('code');
        setcookie('locale', $lang, 0, '/');
        return Redirect::to(route('installer.welcome'));
    }
}
