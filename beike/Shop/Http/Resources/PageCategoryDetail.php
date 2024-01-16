<?php
/**
 * PageCategoryDetail.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-10 11:54:20
 * @modified   2023-02-10 11:54:20
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageCategoryDetail extends JsonResource
{
    public function toArray($request): array
    {
        $description = $this->description;

        return [
            'id'               => $this->id,
            'active'           => $this->active,
            'title'            => $description->title,
            'title_format'     => sub_string($description->title, 64),
            'summary'          => $description->summary,
            'summary_format'   => sub_string($description->summary, 128),
            'meta_title'       => $description->meta_title,
            'meta_description' => $description->meta_description,
            'meta_keywords'    => $description->meta_keywords,
            'created_at'       => time_format($this->created_at),
            'updated_at'       => time_format($this->updated_at),
        ];
    }
}
