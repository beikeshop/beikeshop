<?php
/**
 * OrderProductRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 21:14:12
 * @modified   2022-07-04 21:14:12
 */

namespace Beike\Repositories;

use Beike\Models\Order;
use Beike\Models\OrderProduct;
use Beike\Services\StateMachineService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderProductRepo
{
    /**
     * 创建商品明细
     *
     * @param Order $order
     * @param       $cartProducts
     */
    public static function createOrderProducts(Order $order, $cartProducts)
    {
        $orderProducts = [];
        foreach ($cartProducts as $cartProduct) {
            $productName   = $cartProduct['name'];
            $variantLabels = $cartProduct['variant_labels'] ?? '';
            if ($variantLabels) {
                $productName .= " - {$variantLabels}";
            }
            $orderProducts[] = [
                'product_id'   => $cartProduct['product_id'],
                'order_number' => $order->number,
                'product_sku'  => $cartProduct['product_sku'],
                'name'         => $productName,
                'image'        => $cartProduct['image'],
                'quantity'     => $cartProduct['quantity'],
                'price'        => $cartProduct['price'],
            ];
        }
        $order->orderProducts()->createMany($orderProducts);
    }

    /**
     * 查找单条商品明细数据
     *
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id): Model|Collection|Builder|array|null
    {
        return OrderProduct::query()->findOrFail($id);
    }

    /**
     * @param array $filters
     * @return Builder
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = OrderProduct::query()->with(['order', 'product.description']);

        $order_statuses = $filters['order_statuses'] ?? StateMachineService::getValidStatuses();
        if ($order_statuses) {
            $builder->whereHas('order', function ($query) use ($order_statuses) {
                $query->whereIn('status', $order_statuses);
            });
        } else {
            $builder->whereHas('order', function ($query) {
                $query->where('status', '<>', StateMachineService::CREATED);
            });
        }

        $start = $filters['date_start'] ?? null;
        if ($start) {
            $builder->where('created_at', '>=', $start);
        }

        $end = $filters['date_end'] ?? null;
        if ($end) {
            $builder->where('created_at', '<', Carbon::createFromFormat('Y-m-d', $end)->subDay());
        }

        $order = $filters['order'] ?? null;
        if ($order) {
            $builder->orderBy($order, 'desc');
        }

        $limit = $filters['limit'] ?? null;
        if ($limit) {
            $builder->limit($limit);
        }

        $builder->groupBy(['product_id'])
            ->selectRaw('`product_id`, SUM(`quantity`) AS total_quantity, SUM(`price` * `quantity`) AS total_amount');

        return $builder;
    }
}
