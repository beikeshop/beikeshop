<?php
/**
 * Breadcrumb.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-01 09:38:33
 * @modified   2023-02-01 09:38:33
 */

namespace Beike\Libraries;

use Illuminate\Contracts\View\View;

class Breadcrumb
{
    public array $breadcrumbs;

    /**
     * Create a new component instance.
     *
     * @return void
     * @throws \Exception
     */
    public function __construct()
    {
        $this->breadcrumbs[] = [
            'title' => trans('shop/common.home'),
            'url'   => shop_route('home.index'),
        ];
    }

    /**
     * 获取 Breadcrumb 实例
     * @return Breadcrumb
     */
    public static function getInstance(): self
    {
        return new self;
    }

    /**
     * 添加面包屑节点链接
     *
     * @param $url
     * @param $text
     * @return Breadcrumb
     */
    public function addLink($url, $text): self
    {
        $this->breadcrumbs[] = [
            'url'   => $url,
            'title' => $text,
        ];

        return $this;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|
     */
    public function render(): View
    {
        return view('components.breadcrumbs', ['breadcrumbs' => collect($this->breadcrumbs)]);
    }
}
