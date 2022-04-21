<?php

namespace Beike\Events;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;

class ConfigureRoutes extends Event
{
    /**
     * @var Router
     */
    public $router;

    /**
     * Create a new event instance.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
}
