<?php
/**
 * CurrencyController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 16:17:04
 * @modified   2022-06-30 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CurrencyRequest;
use Beike\Repositories\CurrencyRepo;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected string $defaultRoute = 'currencies.index';

    public function index(Request $request)
    {
        $currencies = CurrencyRepo::all();

        $data = [
            'currencies' => $currencies,
        ];

        return view('admin::pages.currencies.index', $data);
    }

    public function create()
    {
        return view('admin::pages.currencies.form');
    }

    public function store(CurrencyRequest $request)
    {
        $data = [
            'name' => $request->get('name', ''),
            'code' => $request->get('code', ''),
            'symbol_left' => $request->get('symbol_left', ''),
            'symbol_right' => $request->get('symbol_right', ''),
            'decimal_place' => (float)$request->get('decimal_place', 0),
            'value' => (float)$request->get('value', 1),
            'status' => (int)$request->get('status', 0),
        ];
        CurrencyRepo::create($data);

        return redirect($this->getRedirect())->with('success', '货币创建成功！');
    }

    public function edit(Request $request, int $id)
    {
        $data = [
            'currency' => CurrencyRepo::find($id),
            '_redirect' => $this->getRedirect(),
        ];

        return view('admin::pages.currencies.form', $data);
    }

    public function update(CurrencyRequest $request, int $currencyId)
    {
        $data = [
            'name' => $request->get('name', ''),
            'code' => $request->get('code', ''),
            'symbol_left' => $request->get('symbol_left', ''),
            'symbol_right' => $request->get('symbol_right', ''),
            'decimal_place' => (float)$request->get('decimal_place', 0),
            'value' => (float)$request->get('value', 1),
            'status' => (int)$request->get('status', 0),
        ];
        CurrencyRepo::update($currencyId, $data);

        return redirect($this->getRedirect())->with('success', '货币更新成功！');
    }

    public function destroy(Request $request, int $currencyId)
    {
        CurrencyRepo::delete($currencyId);

        return json_success('删除成功！');
    }
}
