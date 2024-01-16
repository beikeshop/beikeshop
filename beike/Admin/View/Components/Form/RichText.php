<?php

namespace Beike\Admin\View\Components\Form;

use Illuminate\View\Component;

class RichText extends Component
{
    public string $name;

    public string $title;

    public $value;

    public bool $required;

    public bool $multiple;

    public function __construct(string $name, string $title, $value = '', bool $required = false, bool $multiple = false)
    {
        $value = html_entity_decode($value, ENT_QUOTES);

        if ($multiple) {
            $value = json_decode($value, true);
        }

        $this->name     = $name;
        $this->title    = $title;
        $this->value    = $value;
        $this->required = $required;
        $this->multiple = $multiple;
    }

    public function render()
    {
        return view('admin::components.form.rich-text');
    }
}
