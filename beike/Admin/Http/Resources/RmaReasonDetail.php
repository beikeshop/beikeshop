<?php
/**
 * RmaReasonDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-31 11:56:28
 * @modified   2022-08-31 11:56:28
 */

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RmaReasonDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'name'  => json_decode($this->name, true)[locale()] ?? '',
            'names' => json_decode($this->name, true),
        ];
    }
}
