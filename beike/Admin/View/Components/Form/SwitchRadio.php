<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class SwitchRadio extends Component
{
    public string $name;

    public string $value;

    public string $title;

    public function __construct(string $name, string $value, string $title)
    {
        $this->name  = $name;
        $this->title = $title;
        $this->value = $value;
    }

    public function render()
    {
        return view('admin::components.form.switch-radio');
    }
}
