<?php
/**
 * PageDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-11 18:45:02
 * @modified   2022-08-11 18:45:02
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageDetail extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $description = $this->description;
        return [
            'id' => $this->id,
            'active' => $this->active,
            'title' => $description->title,
            'title_format' => sub_string($description->title),
            'content' => $description->content,
            'meta_title' => $description->meta_title,
            'meta_description' => $description->meta_description,
            'meta_keyword' => $description->meta_keyword,
            'created_at' => time_format($this->created_at),
            'updated_at' => time_format($this->updated_at),
        ];
    }
}
