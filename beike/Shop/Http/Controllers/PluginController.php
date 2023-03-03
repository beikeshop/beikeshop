<?php
/**
 * PluginController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-03 15:06:40
 * @modified   2023-03-03 15:06:40
 */

namespace Beike\Shop\Http\Controllers;

use Beike\Plugin\Asset;
use Illuminate\Support\Facades\Response;

class PluginController extends Controller
{
    public function asset($code, $path)
    {
        $contents = Asset::getInstance($code)->getContent($path);

        $content = $contents['content'] ?? '';
        $type    = $contents['type']    ?? '';

        if ($content && $type) {
            $response = Response::make($content);
            $response->header('Content-Type', $type);

            return $response;
        }

        return '';
    }
}
