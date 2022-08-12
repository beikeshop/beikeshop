<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Image extends Component
{
    public string $name;
    public string $title;
    public string $value;

    public function __construct(string $name, ?string $title, ?string $value)
    {
        $this->name = $name;
        $this->title = $title ?? '';
        $this->value = $value ?? '';
    }

    public function render()
    {
        return view('admin::components.form.image');
    }
}
