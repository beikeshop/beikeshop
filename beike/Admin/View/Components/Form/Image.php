<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Image extends Component
{
    public string $name;

    public string $title;

    public string $value;

    public string $description;

    public bool $isRemove= false;

    public function __construct(string $name, ?string $title, ?string $value, ?string $description = '', bool $isRemove = false)
    {
        $this->name  = $name;
        $this->title = $title ?? '';
        $this->value = $value ?? '';
        $this->description = $description ?? '';
        $this->isRemove = $isRemove;
    }

    public function render()
    {
        return view('admin::components.form.image');
    }
}
