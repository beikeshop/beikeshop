<?php
/**
 * RmaReasonRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-01 20:42:05
 * @modified   2022-08-01 20:42:05
 */

namespace Beike\Repositories;

use Beike\Models\RmaReason;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RmaReasonRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data)
    {
        $data['name'] = json_encode($data['name']);
        $item         = RmaReason::query()->create($data);

        return $item;
    }

    /**
     * @param $reason
     * @param $data
     * @return Builder|Builder[]|Collection|Model|mixed
     * @throws Exception
     */
    public static function update($reason, $data)
    {
        if (! $reason instanceof RmaReason) {
            $reason = RmaReason::query()->find($reason);
        }
        if (! $reason) {
            throw new Exception("退换货原因id $reason 不存在");
        }
        $data['name'] = json_encode($data['name']);
        $reason->update($data);

        return $reason;
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id)
    {
        return RmaReason::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $reason = RmaReason::query()->find($id);
        if ($reason) {
            $reason->delete();
        }
    }

    /**
     * @param array $data
     * @return Builder[]|Collection
     */
    public static function list(array $data = [])
    {
        $builder = RmaReason::query();

        if (isset($data['name'])) {
            $locale = locale();
            $builder->whereJsonContains("name->$locale", 'like', "%{$data['name']}%");
        }

        return $builder->get();
    }
}
