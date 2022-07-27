<?php
/**
 * CategoryRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-16 17:45:41
 * @modified   2022-06-16 17:45:41
 */

namespace Beike\Repositories;

use Beike\Models\Customer;
use Beike\Models\CustomerWishlist;
use Beike\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CustomerRepo
{
    /**
     * 创建一个customer记录
     * @param $customerData
     * @return Builder|Model
     */
    public static function create($customerData)
    {
        $customerData['password'] = Hash::make($customerData['password']);
        return Customer::query()->create($customerData);
    }

    /**
     * @param $customer
     * @param $data
     * @return bool|int
     */
    public static function update($customer, $data)
    {
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $customer->update($data);
    }

    public static function findByEmail($email)
    {
        return Customer::query()->where('email', $email)->first();
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id)
    {
        return Customer::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        Customer::query()->find($id)->delete();
    }

    /**
     * @param $data
     * @return LengthAwarePaginator
     */
    public static function list($data): LengthAwarePaginator
    {
        $builder = Customer::query()->with("customerGroup.description");

        if (isset($data['name'])) {
            $builder->where('customers.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['email'])) {
            $builder->where('customers.email', 'like', "%{$data['email']}%");
        }
        if (isset($data['status'])) {
            $builder->where('customers.status', $data['status']);
        }
        if (isset($data['from'])) {
            $builder->where('customers.from', $data['from']);
        }
        if (isset($data['customer_group_id'])) {
            $builder->where('customers.customer_group_id', $data['customer_group_id']);
        }

        return $builder->paginate(20)->withQueryString();
    }

    public static function restore($id)
    {
        Customer::withTrashed()->find($id)->restore();
    }

    /**
     * @param $customer ,  Customer对象或id
     * @param $productId
     * @return Customer|Builder|Builder[]|Collection|Model|mixed|null
     */
    public static function addToWishlist($customer, $productId)
    {
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }
        $customer->wishlists()->save(new CustomerWishlist(['product_id' => $productId]));

        return $customer;
    }

    /**
     * @param $customer , Customer对象或id
     * @param $productId
     * @return void
     */
    public static function removeFromWishlist($customer, $productId)
    {
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }
        $customer->wishlists()->where('product_id', $productId)->delete();

        return $customer;
    }

    public static function wishlists($customer): LengthAwarePaginator
    {
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }

        return $customer->wishlists()->with('product.description')->paginate(20);
    }

    /**
     * @param $product , 商品id或对象
     * @param $customer , 顾客id或对象
     * @return int
     */
    public static function inWishlist($product, $customer)
    {
        if (!$customer) {
            return false;
        }
        if ($product instanceof Product) {
            $product = $product->id;
        }
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }
        return $customer->wishlists()->where('product_id', $product)->count();
    }
}

