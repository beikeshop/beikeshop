<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class Image extends Component
{
    public string $name;
    public string $image;
    public string $value;

    public function __construct(string $name, ?string $image, ?string $value)
    {
        $this->name = $name;
        $this->image = $image ?? '';
        $this->value = $value ?? '';
    }

    public function render()
    {
        return view('admin::components.form.image');
    }
}
