<?php

namespace App\Tools\Contracts;

interface PublisherInterface
{
    /**
     * Publish something.
     *
     * @return mixed
     */
    public function publish();
}
