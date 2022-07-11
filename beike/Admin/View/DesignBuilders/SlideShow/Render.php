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

namespace Beike\Admin\View\DesignBuilders\SlideShow;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Render extends Component
{
    private $settings;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($settings = [])
    {
        if ($settings) {
            $this->settings = $settings;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|
     */
    public function render(): View
    {
        return view('design.module.slideshow.render.index', $this->settings);
    }
}
