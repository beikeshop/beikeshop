<?php
/**
 * Base.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-21 18:27:45
 * @modified   2022-07-21 18:27:45
 */

namespace Beike\Models;

use Beike\Models\Scope\SelectScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    }

    public function __get($key)
    {
        $path = storage_path('fields/' . str_replace('\\', '-', get_class($this)) . '.log');
        if (! file_exists($path)) {
            file_put_contents($path, $key . PHP_EOL, 8);
        } else {
            $data = file_get_contents($path);
            $data = array_unique(explode("\n", $data));
            if (! in_array($key, $data)) {
                file_put_contents($path, $key . PHP_EOL, 8);
            }
        }

        return parent::__get($key);
    }

    protected static function booted(): void
    {
        parent::boot();
        static::addGlobalScope(new SelectScope());
    }

    public function getColumns($scene = 'default')
    {
        $model_name  = get_class($this);
        $field_class = str_replace('Models','Fields\Local',$model_name);
        if (class_exists($field_class)) {
           return  (new $field_class)->getColumns($scene);
        }

        return null;
    }

    public function scopeGetList($query)
    {
        if ($columns = $this->getColumns()){
            return $query->select($columns);
        }

        return $query->select();
    }

}
