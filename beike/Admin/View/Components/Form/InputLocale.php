<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class InputLocale extends Component
{
    public string $name;

    public string $title;

    public string $width;

    public string $placeholder;

    public $value;

    public bool $required;

    public function __construct(string $name, string $title, $value, string $width = '400', bool $required = false, string $placeholder = '')
    {
        $this->name        = $name;
        $this->title       = $title;
        $this->width       = $width;
        $this->placeholder = $placeholder;
        $this->value       = $value;
        $this->required    = $required;
    }

    public function render()
    {
        return view('admin::components.form.input-locale');
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
            return old($oldKey, Arr::get($this->value, $code, ''));
        }

        return '';
    }

    public function errorKey($code)
    {
        return str_replace('*', $code, $this->name);
    }
}
