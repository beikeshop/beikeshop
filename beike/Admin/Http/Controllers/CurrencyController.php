<?php
/**
 * CurrencyController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 16:17:04
 * @modified   2022-06-30 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CurrencyRequest;
use Beike\Models\Order;
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
        $data = hook_filter('admin.currency.index.data', $data);

        return view('admin::pages.currencies.index', $data);
    }

    public function store(CurrencyRequest $request)
    {
        $data = [
            'name'          => $request->get('name', ''),
            'code'          => $request->get('code', ''),
            'symbol_left'   => $request->get('symbol_left', ''),
            'symbol_right'  => $request->get('symbol_right', ''),
            'decimal_place' => (float) $request->get('decimal_place', 0),
            'value'         => (float) $request->get('value', 1),
            'status'        => (int) $request->get('status', 0),
        ];
        $currency = CurrencyRepo::create($data);
        hook_action('admin.currency.store.after', $currency);

        return json_success(trans('common.created_success'), $currency);
    }

    public function update(CurrencyRequest $request, int $id)
    {
        $data = [
            'name'          => $request->get('name', ''),
            'code'          => $request->get('code', ''),
            'symbol_left'   => $request->get('symbol_left', ''),
            'symbol_right'  => $request->get('symbol_right', ''),
            'decimal_place' => (float) $request->get('decimal_place', 0),
            'value'         => (float) $request->get('value', 1),
            'status'        => (int) $request->get('status', 0),
        ];
        $currency = CurrencyRepo::update($id, $data);

        return json_success(trans('common.updated_success'), $currency);
    }

    public function destroy(Request $request, int $currencyId)
    {
        try {
            $currency   = CurrencyRepo::find($currencyId);
            $orderExist = Order::query()->where('currency_code', $currency->code)->exists();
            if ($orderExist) {
                throw new \Exception(trans('admin/currency.order_exist'));
            }

            CurrencyRepo::delete($currencyId);
            hook_action('admin.currency.destroy.after', $currencyId);

            return json_success(trans('common.deleted_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
