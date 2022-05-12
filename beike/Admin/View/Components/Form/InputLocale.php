<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class InputLocale extends Component
{
    public string $name;
    public string $title;
    public $value;

    public function __construct(string $name, string $title, $value)
    {
        $this->name = $name;
        $this->title = $title;
        $this->value = $value;
    }

    public function render()
    {
        return view('beike::components.form.input-locale');
    }

    public function formatName($index)
    {
        // descriptions.*.name => descriptions[0][name]

        $segments = explode('.', $this->name);
        $key = $segments[0];
        for ($i = 1; $i < count($segments); $i++) {
            $segment = $segments[$i];
            if ($segment == '*') {
                $key .= '[' . $index . ']';
            } else {
                $key .= '[' . $segment . ']';
            }
        }
        return $key;
    }

    public function formatValue($index)
    {
        $locale = locales()[$index];
        $oldKey = str_replace('*', $index, $this->name);

        // descriptions.*.name
        $segments = explode('.', $this->name);
        array_shift($segments);
        $valueKey = implode('.', $segments);
        $valueKey = str_replace('*', $locale['code'], $valueKey);

        return old($oldKey,  Arr::get($this->value, $valueKey, ''));
    }

    public function errorKey($index)
    {
        return str_replace('*', $index, $this->name);
    }
}
