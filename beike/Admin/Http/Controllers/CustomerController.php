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
            'customers'         => $customers,
            'customers_format'  => CustomerResource::collection($customers)->jsonSerialize(),
            'customer_groups'   => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
            'type'              => 'customer',
            'statuses'          => CustomerRepo::getStatuses(),
        ];
        $data = hook_filter('admin.customer.index.data', $data);
        if ($request->expectsJson()) {
            return json_success(trans('success'), $data);
        }

        return view('admin::pages.customers.index', $data);
    }

    public function trashed(Request $request)
    {
        $customers = CustomerRepo::list(array_merge($request->only(['name', 'email', 'status', 'from', 'customer_group_id']), ['only_trashed' => true]));

        $data = [
            'customers'         => $customers,
            'customers_format'  => CustomerResource::collection($customers)->jsonSerialize(),
            'customer_groups'   => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
            'type'              => 'trashed',
            'statuses'          => CustomerRepo::getStatuses(),
        ];
        $data = hook_filter('admin.customer.trashed.data', $data);
        if ($request->expectsJson()) {
            return json_success(trans('success'), $data);
        }

        return view('admin::pages.customers.index', $data);
    }

    public function store(CustomerRequest $request)
    {
        $requestData = $request->all();

        hook_action('admin.customer.store.before', ['request_data' => $requestData]);

        $customer = CustomerService::create($requestData);

        hook_action('admin.customer.store.after', ['customer_id' => $customer->id, 'request_data' => $requestData]);

        return json_success(trans('common.success'), new CustomerResource($customer));
    }

    public function edit(Request $request, int $customerId)
    {
        $addresses = AddressRepo::listByCustomer($customerId);
        $customer  = CustomerRepo::find($customerId);
        $data      = [
            'customer'          => $customer,
            'customer_groups'   => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
            'addresses'         => AddressResource::collection($addresses)->jsonSerialize(),
            'countries'         => CountryRepo::all(),
            'country_id'        => system_setting('base.country_id'),
            '_redirect'         => $this->getRedirect(),
            'statuses'          => CustomerRepo::getStatuses(),
        ];
        $data = hook_filter('admin.customer.edit.data', $data);

        return view('admin::pages.customers.form', $data);
    }

    public function update(CustomerRequest $request, int $customerId)
    {
        $requestData = $request->all();
        $password    = $requestData['password'] ?? '';
        if (empty($password)) {
            unset($requestData['password']);
        }

        hook_action('admin.customer.update.before', ['customer_id' => $customerId, 'request_data' => $requestData]);

        $customer = CustomerRepo::update($customerId, $requestData);

        hook_action('admin.customer.update.after', ['customer_id' => $customerId, 'request_data' => $requestData]);

        return json_success(trans('common.updated_success'), $customer);
    }

    public function destroy(Request $request, int $customerId)
    {
        CustomerRepo::delete($customerId);
        hook_action('admin.customer.destroy.after', $customerId);

        return json_success(trans('common.deleted_success'));
    }

    public function restore(Request $request, int $customerId)
    {
        CustomerRepo::restore($customerId);
        hook_action('admin.customer.restore.after', $customerId);

        return json_success(trans('common.restored_success'));
    }

    public function forceDelete(Request $request, int $customerId)
    {
        CustomerRepo::forceDelete($customerId);
        hook_action('admin.customer.force_delete.after', $customerId);

        return json_success(trans('common.success'));
    }

    public function forceDeleteAll(Request $request)
    {
        CustomerRepo::forceDeleteAll();
        hook_action('admin.customer.force_delete_all.after', ['module' => 'customer']);

        return json_success(trans('common.success'));
    }

    public function updateActive(Request $request, Customer $customer)
    {
        $customer->active = (bool) $request->get('active');
        $customer->saveOrFail();
        hook_action('admin.customer.update_active.after', $customer);

        return json_success(trans('common.updated_success'));
    }

    public function updateStatus(Request $request, Customer $customer)
    {
        $status = $request->get('status');
        if (in_array($status, Customer::STATUSES)) {
            $customer->status = $status;
            $customer->saveOrFail();
        }
        hook_action('admin.customer.update_status.after', $customer);

        return json_success(trans('common.updated_success'));
    }
}
