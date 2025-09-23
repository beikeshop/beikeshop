<?php

namespace App\Tools\Laravel;

use App\Tools\Laravel\Module;
use App\Tools\FileRepository;

class LaravelFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
