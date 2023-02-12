<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Textarea extends Component
{
    public string $name;

    public string $title;

    public string $value;

    public bool $required;

    public function __construct(string $name, string $title, ?string $value, bool $required = false)
    {
        $this->name     = $name;
        $this->title    = $title;
        $this->value    = html_entity_decode($value, ENT_QUOTES);
        $this->required = $required;
    }

    public function render()
    {
        return view('admin::components.form.textarea');
    }
}
