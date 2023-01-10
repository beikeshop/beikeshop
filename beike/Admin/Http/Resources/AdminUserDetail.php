<?php
/**
 * AdminUserDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-12 15:56:28
 * @modified   2022-08-12 15:56:28
 */

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'locale'     => $this->locale,
            'roles'      => $this->roles,
            'roles_name' => $this->roles->pluck('name')->toArray(),
            'created_at' => time_format($this->created_at),
            'updated_at' => time_format($this->updated_at),
        ];
    }
}
