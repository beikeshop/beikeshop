<?php

namespace Beike\Fields;

class Field
{
    protected array $scene = [];

    public function getColumns($scene = 'default'): array
    {
        if ($data = data_get($this->scene, $scene)) {
            return $data;
        }

        return $this->scene;
    }
}
