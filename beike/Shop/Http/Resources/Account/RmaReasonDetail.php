<?php
/**
 * RmaReasonDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
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
            'id' => $this->id,
            'name' => json_decode($this->name, true)[locale()] ?? '',
        ];
    }
}
