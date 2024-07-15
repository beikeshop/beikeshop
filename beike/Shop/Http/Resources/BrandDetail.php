<?php
/**
 * BrandDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-28 15:33:06
 * @modified   2022-07-28 15:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandDetail extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'logo'       => image_origin($this->logo),
            'sort_order' => $this->sort_order,
            'url'        => $this->url,
            'first'      => $this->first,
        ];
    }
}
