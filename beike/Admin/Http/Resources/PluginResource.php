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
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $currentLocale = locale();

        $name = is_array($this->name) ? ($this->name[$currentLocale] ?? '') : (string) $this->name;
        $description = is_array($this->description) ? ($this->description[$currentLocale] ?? '') : (string) $this->description;

        $data = [
            'code'        => $this->code,
            'name'        => $name,
            'description' => $description,
            'path'        => $this->path,
            'version'     => $this->version,
            'dir_name'    => $this->dirName,
            'type'        => $this->type,
            'type_format' => trans('admin/plugin.' . $this->type),
            'icon'        => plugin_resize($this->code, $this->icon),
            'author'      => $this->author,
            'status'      => $this->getStatus(),
            'installed'   => $this->getInstalled(),
            'edit_url'    => $this->getEditUrl(),
        ];

        return $data;
    }
}
