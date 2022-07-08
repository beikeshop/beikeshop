<?php
/**
 * Render.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-08 17:09:15
 * @modified   2022-07-08 17:09:15
 */

namespace Beike\Shop\View\DesignBuilders\SlideShow;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Editor extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('design.module.slideshow.editor.index');
    }
}
