<?php
/**
 * RmaRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-01 20:42:05
 * @modified   2022-08-01 20:42:05
 */

namespace Beike\Repositories;

use Beike\Models\Customer;
use Beike\Models\Rma;
use Beike\Shop\Http\Resources\RmaDetail;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RmaRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data)
    {
        $item           = Rma::query()->create($data);
        $data['notify'] = 0;
        self::addHistory($item, $data);

        return $item;
    }

    /**
     * @param $rma
     * @param $data
     * @return Builder|Builder[]|Collection|Model|mixed
     * @throws Exception
     */
    public static function update($rma, $data)
    {
        if (! $rma instanceof Rma) {
            $rma = Rma::query()->find($rma);
        }
        if (! $rma) {
            throw new Exception("退还/售后id $rma 不存在");
        }
        $rma->update($data);

        return $rma;
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id)
    {
        return Rma::query()->find($id);
    }

    /**
     * @param       $rma
     * @param array $data
     * @return Rma
     */
    public static function addHistory($rma, array $data)
    {
        if (! $rma instanceof Rma) {
            $rma = self::find($rma);
        }
        $rma->histories()->create($data);
        $rma->status = $data['status'];
        $rma->saveOrFail();

        return $rma;
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $rma = Rma::query()->find($id);
        if ($rma) {
            $rma->delete();
        }
    }

    /**
     * @param $data
     * @return Builder
     */
    public static function getBuilder($data): Builder
    {
        $builder = Rma::query();

        if (isset($data['name'])) {
            $builder->where('name', 'like', "%{$data['name']}%");
        }
        if (isset($data['email'])) {
            $builder->where('email', 'like', "%{$data['email']}%");
        }
        if (isset($data['telephone'])) {
            $builder->where('telephone', 'like', "%{$data['telephone']}%");
        }
        if (isset($data['product_name'])) {
            $builder->where('product_name', 'like', "%{$data['product_name']}%");
        }
        if (isset($data['sku'])) {
            $builder->where('sku', 'like', "%{$data['sku']}%");
        }
        if (isset($data['type'])) {
            $builder->where('type', $data['type']);
        }
        if (isset($data['status'])) {
            $builder->where('status', $data['status']);
        }
        $builder->orderBy('id', 'DESC');

        return $builder;
    }

    /**
     * @param $data
     * @return LengthAwarePaginator
     */
    public static function list($data): LengthAwarePaginator
    {
        return self::getBuilder($data)->paginate(perPage())->withQueryString();
    }

    /**
     * @param $customer
     * @return AnonymousResourceCollection
     */
    public static function listByCustomer($customer): AnonymousResourceCollection
    {
        if (! $customer instanceof Customer) {
            $customer = CustomerRepo::find($customer->id);
        }

        $rmas = $customer->rmas()->orderByDesc('id')->with('reason')->get();

        return RmaDetail::collection($rmas);
    }

    public static function getStatuses(): array
    {
        return [
            'pending'   => trans('rma.status_pending'),
            'rejected'  => trans('rma.status_rejected'),
            'approved'  => trans('rma.status_approved'),
            'shipped'   => trans('rma.status_shipped'),
            'completed' => trans('rma.status_completed'),
        ];
    }

    public static function getTypes(): array
    {
        return [
            'return'   => trans('rma.type_return'),
            'exchange' => trans('rma.type_exchange'),
            'repair'   => trans('rma.type_repair'),
            'reissue'  => trans('rma.type_reissue'),
            'refund'   => trans('rma.type_refund'),
        ];
    }
}
