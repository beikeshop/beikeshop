<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Image extends Component
{
    public string $name;

    public string $title;

    public string $value;

    public string $description;

    public function __construct(string $name, ?string $title, ?string $value, ?string $description = '')
    {
        $this->name  = $name;
        $this->title = $title ?? '';
        $this->value = $value ?? '';
        $this->description = $description ?? '';
    }

    public function render()
    {
        return view('admin::components.form.image');
    }
}
