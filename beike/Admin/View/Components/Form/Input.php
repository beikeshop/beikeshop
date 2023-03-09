<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
{
    public string $name;

    public string $title;

    public string $value;

    public string $error;

    public string $width;

    public string $placeholder;

    public string $type;

    public string $step;

    public bool $required;

    public function __construct(string $name, string $title, string $value, bool $required = false, string $error = '', string $width = '400', string $type = 'text', string $step = '', string $placeholder = '')
    {
        $this->name        = $name;
        $this->title       = $title;
        $this->value       = html_entity_decode($value, ENT_QUOTES);
        $this->error       = $error;
        $this->width       = $width;
        $this->placeholder = $placeholder;
        $this->type        = $type;
        $this->step        = $step;
        $this->required    = $required;
    }

    public function render()
    {
        return view('admin::components.form.input');
    }
}
