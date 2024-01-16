<?php

namespace Beike\Shop\View\Components;

use Illuminate\View\Component;

class NoData extends Component
{
    public string $text;

    public string $link;

    public string $btn;

    public function __construct(?string $text = '', ?string $link = '', ?string $btn = '')
    {
        $this->text = $text ?: trans('common.no_data');
        $this->link = $link;
        $this->btn  = $btn;
    }

    public function render()
    {
        return view('components.no-data');
    }
}
