<?php

namespace App\Rewrite;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewFinderInterface;

class Factory extends \Illuminate\View\Factory
{
    public function __construct(EngineResolver $engines, ViewFinderInterface $finder, Dispatcher $events)
    {
        parent::__construct($engines, $finder, $events);
    }

    /**
     * Create a new view instance from the given arguments.
     *
     * @param string          $view
     * @param string          $path
     * @param Arrayable|array $data
     * @return ViewContract
     */
    protected function viewInstance($view, $path, $data): ViewContract
    {
        return new View($this, $this->getEngineFromPath($path), $view, $path, $data);
    }
}
