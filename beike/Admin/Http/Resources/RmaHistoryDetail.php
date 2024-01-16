<?php
/**
 * RmaHistoryDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-09-30 11:56:28
 * @modified   2022-09-30 11:56:28
 */

namespace Beike\Admin\Http\Resources;

use Beike\Repositories\RmaRepo;
use Illuminate\Http\Resources\Json\JsonResource;

class RmaHistoryDetail extends JsonResource
{
    public function toArray($request): array
    {
        $statuses = RmaRepo::getStatuses();

        return [
            'id'         => $this->id,
            'rma_id'     => $this->rma_id,
            'status'     => $statuses[$this->status],
            'created_at' => time_format($this->created_at),
            'notify'     => $this->notify,
            'comment'    => $this->comment,
        ];
    }
}
