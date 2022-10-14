<?php
/**
 * CategoryRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
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
        $customerData['email'] = $customerData['email'] ?? '';
        $customerData['password'] = Hash::make($customerData['password'] ?? '');
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
     * @throws \Exception
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
        $builder = self::getListBuilder($data);
        return $builder->paginate(20)->withQueryString();
    }


    /**
     * 获取筛选对象
     *
     * @param array $filters
     * @return Builder
     */
    public static function getListBuilder(array $filters = []): Builder
    {
        $builder = Customer::query()->with("customerGroup.description");

        if (isset($filters['name'])) {
            $builder->where('customers.name', 'like', "%{$filters['name']}%");
        }
        if (isset($filters['email'])) {
            $builder->where('customers.email', 'like', "%{$filters['email']}%");
        }
        if (isset($filters['status'])) {
            $builder->where('customers.status', (int)$filters['status']);
        }
        if (isset($filters['from'])) {
            $builder->where('customers.from', $filters['from']);
        }
        if (isset($filters['customer_group_id'])) {
            $builder->where('customers.customer_group_id', $filters['customer_group_id']);
        }

        $start = $filters['start'] ?? null;
        if ($start) {
            $builder->where('customers.created_at', '>', $start);
        }

        $end = $filters['end'] ?? null;
        if ($end) {
            $builder->where('customers.created_at', '<', $end);
        }

        return $builder;
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

        if (!$customer->wishlists()->where('product_id', $productId)->first()) {
            $wishlist = $customer->wishlists()->save(new CustomerWishlist(['product_id' => $productId]));
        }

        return $wishlist;
    }

    /**
     * @param $customer , Customer对象或id
     * @param $id
     * @return void
     */
    public static function removeFromWishlist($customer, $id)
    {
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }
        $customer->wishlists()->where('id', $id)->delete();

        return $customer;
    }

    public static function wishlists($customer): LengthAwarePaginator
    {
        if (!$customer instanceof Customer) {
            $customer = Customer::query()->findOrFail($customer);
        }
        $builder = $customer->wishlists()
            ->whereHas('product');

        return $builder->with('product.description')->paginate(20);
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

