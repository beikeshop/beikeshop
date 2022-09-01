<?php

namespace Beike\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class NoData extends Component
{
    public string $text;

    public function __construct(?string $text = '')
    {
        $this->text = $text ?: trans('common.no_data');
    }

    public function render()
    {
        return view('components.no-data');
    }
}
