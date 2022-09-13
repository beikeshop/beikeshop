<?php
/**
 * CustomerController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-28 16:17:04
 * @modified   2022-06-28 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CustomerRequest;
use Beike\Admin\Http\Resources\AddressResource;
use Beike\Admin\Http\Resources\CustomerGroupDetail;
use Beike\Admin\Http\Resources\CustomerResource;
use Beike\Admin\Services\CustomerService;
use Beike\Models\Customer;
use Beike\Repositories\AddressRepo;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\CustomerRepo;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = CustomerRepo::list($request->only(['name', 'email', 'status', 'from', 'customer_group_id']));

        $data = [
            'customers' => $customers,
            'customers_format' => CustomerResource::collection($customers)->jsonSerialize(),
            'customer_groups' => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
        ];

        if ($request->expectsJson()) {
            return json_success(trans('success'), $data);
        }

        return view('admin::pages.customers.index', $data);
    }

    public function store(CustomerRequest $request)
    {
        $data = $request->only(['email', 'name', 'password', 'status', 'customer_group_id']);
        $customer = CustomerService::create($data);

        return json_success(trans('common.success'), new CustomerResource($customer));
    }

    public function edit(Request $request, int $customerId)
    {
        $addresses = AddressRepo::listByCustomer($customerId);
        $customer = CustomerRepo::find($customerId);
        $data = [
            'customer' => $customer,
            'customer_groups' => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
            'addresses' => AddressResource::collection($addresses)->jsonSerialize(),
            'countries' => CountryRepo::all(),
            'country_id' => system_setting('base.country_id'),
            '_redirect' => $this->getRedirect(),
        ];

        return view('admin::pages.customers.form', $data);
    }

    public function update(CustomerRequest $request, int $customerId)
    {
        $data = $request->only(['email', 'name', 'status', 'customer_group_id']);
        if ($request->get('password')) {
            $data['password'] = $request->get('password');
        }
        $customer = CustomerRepo::update($customerId, $data);

        return json_success(trans('common.updated_success'), $customer);
    }

    public function destroy(Request $request, int $customerId)
    {
        CustomerRepo::delete($customerId);

        return json_success(trans('common.deleted_success'));
    }

    public function restore(Request $request, int $customerId)
    {
        CustomerRepo::restore($customerId);

        return json_success(trans('common.restored_success'));
    }
}
