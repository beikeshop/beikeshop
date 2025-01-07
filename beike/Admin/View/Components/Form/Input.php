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

    public string $description;

    public string $type;

    public string $step;

    public string $groupLeft;

    public string $groupRight;

    public bool $required;

    public bool $disabled;

    public function __construct(string $name, string $title, string $value, bool $required = false, string $error = '', string $width = '400', string $type = 'text', string $step = '', string $placeholder = '', string $description = '', string $groupLeft = '', string $groupRight = '', bool $disabled = false)
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
        $this->description = $description;
        $this->groupLeft   = $groupLeft;
        $this->groupRight  = $groupRight;
        $this->disabled    = $disabled;
    }

    public function render()
    {
        return view('admin::components.form.input');
    }
}
