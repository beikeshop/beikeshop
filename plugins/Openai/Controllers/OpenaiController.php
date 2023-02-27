<?php
/**
 * OpenaiController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-27 16:13:08
 * @modified   2023-02-27 16:13:08
 */

namespace Plugin\Openai\Controllers;

use Beike\Admin\Http\Controllers\Controller;

class OpenaiController extends Controller
{
    public function index()
    {
        $plugin = app('plugin')->getPlugin('openai');
        $data   = [
            'name'        => $plugin->getLocaleName(),
            'description' => $plugin->getLocaleDescription(),
        ];

        return view('Openai::admin.openai', $data);
    }
}
