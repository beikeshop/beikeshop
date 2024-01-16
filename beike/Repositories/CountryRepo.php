<?php
/**
 * CountryRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 15:22:05
 * @modified   2022-06-30 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\Country;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CountryRepo
{
    /**
     * @param $data
     * @return array
     */
    private static function handleParams($data)
    {
        return [
            'name'       => $data['name']             ?? '',
            'code'       => $data['code']             ?? '',
            'continent'  => $data['continent']        ?? '',
            'sort_order' => (int) $data['sort_order'] ?? 0,
            'status'     => (bool) $data['status']    ?? 0,
        ];
    }

    /**
     * 创建一个country记录
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        $data = self::handleParams($data);

        return Country::query()->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function update($id, $data)
    {
        $data    = self::handleParams($data);
        $country = Country::query()->find($id);
        if (! $country) {
            throw new \Exception("国家id {$id} 不存在");
        }
        $country->update($data);

        return $country;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        return Country::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $country = Country::query()->find($id);
        if ($country) {
            $country->delete();
        }
    }

    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public static function list(array $filters = []): LengthAwarePaginator
    {
        $builder = self::getBuilder($filters);

        return $builder->paginate(perPage())->withQueryString();
    }

    /**
     * @param array $filters
     * @return Builder
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = Country::query();

        if (isset($filters['name'])) {
            $builder->where('countries.name', 'like', "%{$filters['name']}%");
        }
        if (isset($filters['code'])) {
            $builder->where('countries.code', 'like', "%{$filters['code']}%");
        }
        if (isset($filters['status'])) {
            $builder->where('countries.status', $filters['status']);
        }
        if (isset($filters['continent'])) {
            $continent = $filters['continent'];
            if ($continent == 'null') {
                $continent = '';
            }
            $builder->where('countries.continent', $continent);
        }

        return $builder;
    }

    /**
     * 获取已启用国家列表
     * @return Builder[]|Collection
     */
    public static function listEnabled(): Collection|array
    {
        return Country::query()->where('status', true)->select('id', 'name')->get();
    }

    /**
     * 获取所有国家列表
     * @return Builder[]|Collection
     */
    public static function all()
    {
        return Country::query()->select('id', 'name')->get();
    }

    /**
     * @return array
     */
    public static function getContinents(): array
    {
        $result = [];
        foreach (Country::CONTINENTS as $continent) {
            $result[] = [
                'code'  => $continent,
                'label' => trans("country.{$continent}"),
            ];
        }

        return $result;
    }
}
