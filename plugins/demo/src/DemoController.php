<?php
/**
 * DemoController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-20 11:26:06
 * @modified   2022-04-20 11:26:06
 */

namespace Beike\Demo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        ['amount' => $amount] = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);
        dd($amount);
        return view('demo::index');
    }
}
