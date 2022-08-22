<?php
/**
 * TaxClassDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-12 15:56:28
 * @modified   2022-08-12 15:56:28
 */

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxClassDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => sub_string($this->description),
            'created_at' => time_format($this->created_at),
            'updated_at' => time_format($this->updated_at),
        ];
    }
}
