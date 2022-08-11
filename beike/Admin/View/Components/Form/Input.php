<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
{
    public string $name;
    public string $title;
    public string $value;
    public bool $required;

    public function __construct(string $name, string $title, ?string $value, bool $required = false)
    {
        $this->name = $name;
        $this->title = $title;
        $this->value = $value ?? '';
        $this->required = $required ?? false;
    }

    public function render()
    {
        return view('admin::components.form.input');
    }
}
