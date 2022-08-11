<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Textarea extends Component
{
    public string $name;
    public string $title;
    public string $value;
    public bool $required;
    public bool $html;

    public function __construct(string $name, string $title, ?string $value, bool $required = false, bool $html = false)
    {
        $this->name = $name;
        $this->title = $title;
        $this->value = $value;
        $this->required = $required;
        $this->html = $html;
    }

    public function render()
    {
        return view('admin::components.form.textarea');
    }
}
