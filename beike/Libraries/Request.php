<?php

namespace Beike\Libraries;

class Request extends \Illuminate\Http\Request
{
    /**
     *  rewrite path info
     *
     * @return string
     */
    public function getPathInfo(): string
    {
        $uri = $this->pathInfo ??= $this->preparePathInfo();

        return $this->replaceUri($uri);
    }

    /**
     *  rewrite request uri
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        $uri = $this->requestUri ??= $this->prepareRequestUri();

        return $this->replaceUri($uri);
    }

    /**
     *  get langs
     *
     * @return array
     */
    private function getLang(): array
    {
        return array_map(function ($item) {
            return '/' . $item;
        }, config('app.langs'));
    }

    /**
     *  replace uri
     *
     * @param $uri
     * @return array|string
     */
    private function replaceUri($uri): array|string
    {
        $langs = $this->getLang();
        foreach ($langs as $lang) {
            if (str_starts_with($uri, '/lang' . $lang)) {
                return $uri;
            }
        }

        session()->put('originalUri', $uri);
        foreach ($langs as $item) {
            $uriArr = explode('/', $uri);
            if (count($uriArr) && in_array(trim($item, '/'), $uriArr)) {
                $uri = str_replace($item, '', $uri);

                break;
            }
        }

        return $uri;
    }
}
