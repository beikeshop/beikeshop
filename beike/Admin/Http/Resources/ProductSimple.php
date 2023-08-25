<?php
/**
 * ProductSimple.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-22 15:06:08
 * @modified   2023-08-22 15:06:08
 */

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSimple extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'           => $this->id,
            'name'         => $this->description->name,
            'image'        => $this->image,
            'image_format' => image_resize($this->image),
            'status'       => $this->active,
        ];

        return hook_filter('resource.product.simple', $data);
    }
}
