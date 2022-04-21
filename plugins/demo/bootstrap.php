<?php
/**
 * bootstrap.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-19 18:59:04
 * @modified   2022-04-19 18:59:04
 */

namespace Beike\Demo;

use Beike\Plugin\Hook;
use Illuminate\Routing\Route;

return function () {
    // dd('demo bootstrap');
    // Hook::addMenuItem('user', 3, [
    //     'title' => 'Blessing\ConfigGenerator::config.generate-config',
    //     'link' => 'user/config',
    //     'icon' => 'fa-book',
    // ]);

    Hook::addRoute(function () {
        Route::get(
            'demo1',
            'Beike\Demo\DemoController@index'
        )->middleware(['web', 'auth']);
    });
    app()->make('router')->get('demo2', 'Beike\Demo\DemoController@index');
};
