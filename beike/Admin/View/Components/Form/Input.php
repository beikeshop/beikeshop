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
    public string $type;
    public string $step;
    public bool $required;

    public function __construct(string $name, string $title, ?string $value, bool $required = false, ?string $error = '', ?string $width = '400', ?string $type = 'text', ?string $step = '')
    {
        $this->name = $name;
        $this->title = $title;
        $this->value = $value;
        $this->error = $error;
        $this->width = $width;
        $this->type = $type;
        $this->step = $step;
        $this->required = $required;
    }

    public function render()
    {
        return view('admin::components.form.input');
    }
}
