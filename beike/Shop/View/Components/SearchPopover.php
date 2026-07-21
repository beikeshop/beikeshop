<?php

namespace Beike\Shop\View\Components;

use Illuminate\View\Component;

class SearchPopover extends Component
{
    public function __construct() {}

    public function render()
    {
        $systemKeywords = system_setting('base.hot_keywords')[locale()] ?? '';
        $hotKeywords    = $systemKeywords ? explode(',', $systemKeywords) : [];

        $data = [
            'hot_keywords' => $hotKeywords,
        ];

        return view('components.search-popover', $data);
    }
}
