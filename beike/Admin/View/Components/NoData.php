<?php

namespace Beike\Admin\View\Components;

use Illuminate\View\Component;

class NoData extends Component
{
    public string $text;

    public function __construct(?string $text = '')
    {
        $this->text = $text ?: trans('common.no_data');
    }

    public function render()
    {
        return view('admin::components.no-data');
    }
}
