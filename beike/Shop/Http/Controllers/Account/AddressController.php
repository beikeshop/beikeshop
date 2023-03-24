<?php
/**
 * AddressController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-28 20:17:04
 * @modified   2022-06-28 20:17:04
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\AddressRepo;
use Beike\Repositories\CountryRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\AddressRequest;
use Beike\Shop\Http\Resources\Account\AddressResource;
use Beike\Shop\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = AddressRepo::listByCustomer(current_customer());
        $data      = [
            'countries' => CountryRepo::listEnabled(),
            'addresses' => AddressResource::collection($addresses),
        ];

        return view('account/address', $data);
    }

    public function show(Request $request, $id)
    {
        $address = AddressRepo::find($id);

        return json_success(trans('common.get_success'), new AddressResource($address));
    }

    public function store(AddressRequest $request)
    {
        $data    = $request->only(['name', 'phone', 'country_id', 'zone_id', 'city_id', 'city', 'zipcode', 'address_1', 'address_2', 'default']);
        $address = AddressService::create($data);

        return json_success(trans('common.created_success'), new AddressResource($address));
    }

    public function update(AddressRequest $request, int $id)
    {
        $data    = $request->only(['name', 'phone', 'country_id', 'zone_id', 'city_id', 'city', 'zipcode', 'address_1', 'address_2', 'default']);
        $address = AddressService::update($id, $data);

        return json_success(trans('common.updated_success'), new AddressResource($address));
    }

    public function destroy(Request $request, int $id)
    {
        AddressRepo::delete($id);

        return json_success(trans('common.deleted_success'));
    }
}
