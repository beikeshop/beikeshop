<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Checkbox extends Component
{
    public string $name;

    public string $value;

    public string $class;

    public function __construct(
        string $name,
        string $value,
        ?string $class = ''
    ) {
        $this->name    = $name;
        $this->value   = $value;
        $this->class   = $class ?: '';
    }

    public function render()
    {
        return view('admin::components.form.checkbox');
    }
}
