<?php
/**
 * RmaReasonDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-31 11:56:28
 * @modified   2022-08-31 11:56:28
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class RmaReasonDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => json_decode($this->name, true)[locale()] ?? '',
        ];
    }
}
