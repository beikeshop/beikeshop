<?php

namespace Beike\Admin\View\Components;

use Illuminate\View\Component;

class Filter extends Component
{
    public string $url;

    public array $queries;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url     = $url;
        $this->queries = request()->query() ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('Resources::components.admin.filter');
    }
}
