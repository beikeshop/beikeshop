<?php

namespace App\Tools\Lumen;

use App\Tools\Lumen\Module;
use App\Tools\FileRepository;

class LumenFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
