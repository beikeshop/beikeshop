<?php
/**
 * ThemeRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-06 17:06:11
 * @modified   2023-02-06 17:06:11
 */

namespace Beike\Repositories;

use Illuminate\Support\Str;

class ThemeRepo
{
    public static function getAllThemes()
    {
        $path       = base_path('themes');
        $themePaths = glob($path . '/*');
        $themes     = [];
        foreach ($themePaths as $themePath) {
            $theme    = trim(str_replace($path, '', $themePath), '/');
            $themes[] = [
                'value' => $theme,
                'label' => Str::studly($theme),
            ];
        }

        return $themes;
    }
}
