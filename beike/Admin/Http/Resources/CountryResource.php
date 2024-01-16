<?php
/**
 * CountryResource.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-12-08 15:06:30
 * @modified   2023-12-08 15:06:30
 */

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'code'             => $this->code,
            'continent'        => $this->continent,
            'continent_format' => trans('country.' . ($this->continent ?: 'null')),
            'sort_order'       => $this->sort_order,
            'status'           => $this->status,
            'created_at'       => time_format($this->created_at),
            'updated_at'       => time_format($this->updated_at),
        ];
    }
}
