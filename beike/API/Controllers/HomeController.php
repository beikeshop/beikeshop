<?php
/**
 * HomeController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-06-06 15:50:32
 * @modified   2023-06-06 15:50:32
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return system_setting('base.design_setting');
    }
}
