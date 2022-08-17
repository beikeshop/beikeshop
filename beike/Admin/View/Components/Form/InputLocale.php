<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class InputLocale extends Component
{
    public string $name;
    public string $title;
    public string $width;
    public $value;
    public bool $required;

    public function __construct(string $name, string $title, $value, ?string $width = '400', ?bool $required = false)
    {
        $this->name = $name;
        $this->title = $title;
        $this->width = $width;
        $this->value = $value;
        $this->required = $required;
    }

    public function render()
    {
        return view('admin::components.form.input-locale');
    }

    public function formatName(string $code)
    {
        // descriptions.*.name => descriptions[zh_cn][name]

        $segments = explode('.', $this->name);
        $key = $segments[0];
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

    public function formatValue($code)
    {
        $oldKey = str_replace('*', $code, $this->name);

        // descriptions.*.name
        $segments = explode('.', $this->name);
        array_shift($segments);
        $valueKey = implode('.', $segments);
        $valueKey = str_replace('*', $code, $valueKey);

        return old($oldKey,  Arr::get($this->value, $valueKey, ''));
    }

    public function errorKey($code)
    {
        return str_replace('*', $code, $this->name);
    }
}
