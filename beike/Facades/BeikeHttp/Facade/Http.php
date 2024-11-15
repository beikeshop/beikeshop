<?php

namespace Beike\Facades\BeikeHttp\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed sendGet(string $apiEndPoint, array|string $query = null,$format = 'json')
 * @method static mixed sendPost(string $apiEndPoint, string $body = '', array $data = [],$format = 'json')
 */
class Http extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'beike_http';
    }
}
