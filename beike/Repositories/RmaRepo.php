<?php
/**
 * RmaRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-01 20:42:05
 * @modified   2022-08-01 20:42:05
 */

namespace Beike\Repositories;

use Beike\Models\Rma;
use Beike\Models\Customer;
use Beike\Shop\Http\Resources\RmaDetail;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RmaRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data)
    {
        $item = Rma::query()->create($data);
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
        if (!$rma instanceof Rma) {
            $rma = Rma::query()->find($rma);
        }
        if (!$rma) {
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
     * @param $rma
     * @param array $data
     * @return Rma
     */
    public static function addHistory($rma, Array $data)
    {
        if (!$rma instanceof Rma) {
            $rma = self::find($rma);
        }
        $rma->histories()->create($data);
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
     * @return LengthAwarePaginator
     */
    public static function list($data): LengthAwarePaginator
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
        if (isset($data['model'])) {
            $builder->where('model', 'like', "%{$data['model']}%");
        }
        if (isset($data['type'])) {
            $builder->where('type', $data['type']);
        }
        if (isset($data['status'])) {
            $builder->where('status', $data['status']);
        }

        return $builder->paginate(10)->withQueryString();
    }

    /**
     * @param $customer
     * @return array
     */
    public static function listByCustomer($customer): array
    {
        if (!$customer instanceof Customer) {
            $customer = CustomerRepo::find($customer->id);
        }

        $results = [];
        foreach ($customer->rmas as $rma) {
            $results[$rma->first][] = (new RmaDetail($rma))->jsonSerialize();
        }

        return $results;
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => '待处理',
            'rejected' => '已拒绝',
            'approved' => '已批准（待顾客寄回商品）',
            'shipped' => '已发货（寄回商品）',
            'completed' => '已完成'
        ];
    }

    public static function getTypes(): array
    {
        return [
            'return' => '退货',
            'exchange' => '换货',
            'repair' => '维修',
            'reissue' => '补发商品',
            'refund' => '仅退款'
        ];
    }
}
