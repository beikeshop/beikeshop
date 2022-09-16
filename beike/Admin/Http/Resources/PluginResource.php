<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PluginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'name' => $this->name,
            'version' => $this->version,
            'dir_name' => $this->dirName,
            'path' => $this->path,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'type_format' => trans('admin/plugin.' . $this->type),
            'icon' => plugin_resize($this->code, $this->icon),
            'author' => $this->author,
            'status' => $this->getStatus(),
            'installed' => $this->getInstalled(),
            'edit_url' => $this->getEditUrl(),
        ];

        return $data;
    }
}
