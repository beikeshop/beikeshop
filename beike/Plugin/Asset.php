<?php
/**
 * Asset.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-03 14:50:31
 * @modified   2023-03-03 14:50:31
 */

namespace Beike\Plugin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Asset
{
    private $pluginPath;

    public const CONTENT_TYPES = [
        'js'   => 'application/javascript',
        'css'  => 'text/css',
        'jpg'  => 'image/jpeg',
        'apng' => 'image/apng',
        'avif' => 'image/avif',
        'gif'  => 'image/gif',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'svg'  => 'image/svg+xml',
        'webp' => 'image/webp',
        'webm' => 'video/webm',
        'ogg'  => 'video/ogg',
    ];

    public function __construct($pluginCode)
    {
        if (empty($pluginCode)) {
            throw new \Exception('Empty plugin code!');
        }
        $folderName       = Str::studly($pluginCode);
        $this->pluginPath = base_path('plugins/' . $folderName);
    }

    public static function getInstance($pluginCode)
    {
        return new self($pluginCode);
    }

    /**
     * Get content and type
     *
     * @param $file
     * @return array|string
     */
    public function getContent($file)
    {
        $filePath = $this->pluginPath . '/Static/' . $file;
        if (is_file($filePath)) {
            $extension = File::extension($filePath);

            return [
                'type'    => self::CONTENT_TYPES[$extension] ?? '',
                'content' => file_get_contents($filePath),
            ];
        }

        return [];
    }
}
