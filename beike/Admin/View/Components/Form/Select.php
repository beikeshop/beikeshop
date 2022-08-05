<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public string $name;
    public string $value;
    public string $title;
    public array $options;

    public function __construct(string $name, string $value, string $title, array $options)
    {
        $this->name = $name;
        $this->title = $title;
        $this->value = $value;
        $this->options = $options;
    }

    public function render()
    {
        return view('admin::components.form.select');
    }
}
