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

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\AddressResource;
use Beike\Admin\Services\AddressService;
use Beike\Repositories\AddressRepo;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request, int $customerId)
    {
        $addresses = AddressRepo::listByCustomer($customerId);
        $data      = [
            'addresses' => AddressResource::collection($addresses),
        ];

        return hook_filter('admin.address.index.data', $data);
    }

    public function store(Request $request, int $customerId)
    {
        $requestData = $request->all();

        $beforeData = [
            'customer_id' => $customerId,
            'data'        => $requestData,
        ];
        hook_action('admin.address.store.before', $beforeData);
        $address = AddressService::addForCustomer($customerId, $requestData);
        hook_action('admin.address.store.after', $address);

        return json_success(trans('common.created_success'), $address);
    }

    public function update(Request $request, int $customerId, int $addressId)
    {
        $requestData = $request->all();
        $beforeData  = [
            'customer_id' => $customerId,
            'address_id'  => $addressId,
            'data'        => $requestData,
        ];
        hook_action('admin.address.update.before', $beforeData);
        $address = AddressService::update($addressId, $request->all());
        hook_action('admin.address.update.after', $address);

        return json_success(trans('common.updated_success'), $address);
    }

    public function destroy(Request $request, int $customerId, int $addressId)
    {
        AddressRepo::delete($addressId);
        hook_action('admin.address.destroy.after', $addressId);

        return json_success(trans('common.deleted_success'));
    }
}
