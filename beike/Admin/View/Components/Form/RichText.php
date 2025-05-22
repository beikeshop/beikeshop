<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class RichText extends Component
{
    public string $name;

    public string $title;

    public $value;

    public bool $required;

    public string $error;

    public bool $multiple;

    public function __construct(string $name, string $title, $value = '', bool $required = false, bool $multiple = false, string $error = '')
    {
        $value = html_entity_decode($value, ENT_QUOTES);

        if ($multiple) {
            $value = json_decode($value, true);
        }

        $this->name     = $name;
        $this->title    = $title;
        $this->value    = $value;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->error    = $error;
    }

    public function render()
    {
        return view('admin::components.form.rich-text');
    }

    public function formatName(string $code)
    {
        // descriptions.*.name => descriptions[zh_cn][name]

        $segments = explode('.', $this->name);
        $key      = $segments[0];
        for ($i = 1; $i < count($segments); $i++) {
            $segment = $segments[$i];
            if ($segment == '*') {
                $key .= '[' . $code . ']';
            } else {
                $key .= '[' . $segment . ']';
            }
        }

        return $key;
    }

    /**
     * Get value from database
     *
     * @param $code
     * @return mixed
     */
    public function formatValue($code)
    {
        $oldKey = str_replace('*', $code, $this->name);

        if (is_string($this->value)) {
            $value = json_decode($this->value, true);

            return old($oldKey, Arr::get($value, $code, ''));
        } elseif ($this->value instanceof Collection) {
            // descriptions.*.name
            $segments = explode('.', $this->name);
            array_shift($segments);
            $valueKey = implode('.', $segments);
            $valueKey = str_replace('*', $code, $valueKey);

            return old($oldKey, Arr::get($this->value, $valueKey, ''));
        } elseif (is_array($this->value)) {
            $value = Arr::get($this->value, $code, '');
            $segments = explode('.', $this->name);
            $valueKey = end($segments);

            if (is_array($value)) {
                $value = $value[$valueKey];
            }

            return old($oldKey, $value);
        }

        return '';
    }

    public function errorKey($code)
    {
        return str_replace('*', $code, $this->name);
    }
}
