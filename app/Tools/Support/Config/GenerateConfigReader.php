<?php

namespace App\Tools\Support\Config;

use App\Tools\Support\Config\GeneratorPath;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("plugins.paths.generator.$value"));
    }
}
