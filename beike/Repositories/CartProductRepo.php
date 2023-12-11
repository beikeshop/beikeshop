<?php
/**
 * CartProductRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-12-06 17:14:14
 * @modified   2023-12-06 17:14:14
 */

namespace Beike\Repositories;

use Beike\Models\Cart;
use Beike\Models\CartProduct;
use Beike\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CartProductRepo
{
    /**
     * @param $filters
     * @return LengthAwarePaginator
     */
    public static function list($filters): LengthAwarePaginator
    {
        $builder = self::getListBuilder($filters);

        return $builder->paginate(perPage())->withQueryString();
    }

    /**
     * @param array $filters
     * @return Builder
     */
    public static function getListBuilder(array $filters = []): Builder
    {
        $builder = CartProduct::query()->with(['sku', 'product']);

        $customer = $filters['customer'] ?? null;
        if ($customer) {
            $builder->whereHas('customer', function ($query) use ($customer) {
                $query->where('name', 'like', "%{$customer}%")
                    ->orWhere('email', 'like', "%{$customer}%");
            });
        }

        $product = $filters['product'] ?? null;
        if ($product) {
            $builder->where(function($query) use ($product) {
                $query->whereHas('product.description', function ($query) use ($product) {
                        $query->where('name', 'like', "%{$product}%");
                    })
                    ->orWereHas('product', function ($query) use ($product) {
                        $query->where('sku', 'like', "%{$product}%");
                    });
            });
        }

        $selected = $filters['selected'] ?? null;
        if (isset($selected)) {
            $builder->where('selected', $selected);
        }

        $createdAt = $filters['created_at'] ?? null;
        if ($createdAt) {
            $builder->where('created_at', '>', $createdAt);
        }

        $updatedAt = $filters['updated_at'] ?? null;
        if ($updatedAt) {
            $builder->where('updated_at', '>', $updatedAt);
        }

        $builder->orderByDesc('customer_id');

        return $builder;
    }
}
