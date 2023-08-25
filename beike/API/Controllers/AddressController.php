<?php
/**
 * AddressController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-15 11:02:22
 * @modified   2023-08-15 11:02:22
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\Address;
use Beike\Repositories\AddressRepo;
use Beike\Shop\Http\Requests\AddressRequest;
use Beike\Shop\Http\Resources\Account\AddressResource;
use Beike\Shop\Services\AddressService;

class AddressController extends Controller
{
    public function index()
    {
        $customer = current_customer();
        if (empty($customer)) {
            return json_success(trans('common.get_success'));
        }

        $addresses = AddressRepo::listByCustomer($customer);

        return AddressResource::collection($addresses);
    }

    public function show(Address $address)
    {
        $customer = current_customer();
        if (empty($customer)) {
            return json_success(trans('common.get_success'));
        }

        return json_success(trans('common.get_success'), new AddressResource($address));
    }

    public function store(AddressRequest $request)
    {
        $data    = $request->all();
        $address = AddressService::create($data);

        return json_success(trans('common.created_success'), new AddressResource($address));
    }

    public function update(AddressRequest $request, Address $address)
    {
        $data    = $request->all();
        $address = AddressService::update($address->id, $data);

        return json_success(trans('common.updated_success'), new AddressResource($address));
    }

    public function destroy(Address $address)
    {
        AddressRepo::delete($address->id);

        return json_success(trans('common.deleted_success'));
    }
}
