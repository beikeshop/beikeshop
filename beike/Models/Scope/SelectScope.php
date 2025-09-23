<?php

namespace Beike\Models\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SelectScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $model_name  = get_class($model);
        $field_class = str_replace('Models','Fields\Global',$model_name);
        if (class_exists($field_class)) {
            if ($columns = (new $field_class)->getColumns()) {
               // $builder->select($columns);
                //$builder->addSelect($columns);
            }
        }
    }
}
