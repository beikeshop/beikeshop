<?php
/**
 * BrandDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-28 15:33:06
 * @modified   2022-07-28 15:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => image_resize($this->logo),
            'sort_order' => $this->sort_order,
            'first' => $this->first,
        ];
    }
}
