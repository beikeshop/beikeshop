<?php

namespace Beike\Hook;

class Callback
{
    protected $function;

    protected array $parameters = [];

    protected bool $run = true;

    public function __construct($function, $parameters = [])
    {
        $this->setCallback($function, $parameters);
    }

    public function setCallback($function, $parameters)
    {
        $this->function   = $function;
        $this->parameters = $parameters;
    }

    public function call($parameters = null)
    {
        if ($this->run) {
            $this->run = false;

            return call_user_func_array($this->function, ($parameters ?: $this->parameters));
        }
    }

    public function reset()
    {
        $this->run = true;
    }
}
