<?php
/**
 * Render.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-08 17:09:15
 * @modified   2022-07-08 17:09:15
 */

namespace Beike\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image401 extends Component
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
        $data['register'] = [
            'code' => 'image401',
            'sort' => 0,
            'name' => trans('admin/design_builder.module_four_image_pro'),
            'icon' => asset('image/module/image_401.png'),
        ];

        return view('admin::pages.design.module.image401', $data);
    }
}
