<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public string $name;

    public string $value;

    public string $title;

    public array $options;

    public string $width;

    public string $key;

    public string $label;

    public function __construct(string $name, string $value, string $title, array $options, string $width = '400', ?string $key = 'value', ?string $label = 'label')
    {
        $this->name    = $name;
        $this->title   = $title;
        $this->value   = $value;
        $this->options = $options;
        $this->width   = $width;
        $this->key     = $key;
        $this->label   = $label;
    }

    public function render()
    {
        return view('admin::components.form.select');
    }
}
