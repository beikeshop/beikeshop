<?php
/**
 * ImageService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-12 17:30:20
 * @modified   2022-07-12 17:30:20
 */

namespace Beike\Services;

use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;

class ImageService
{
    private $image;
    private $imagePath;

    const PLACEHOLDER_IMAGE = 'catalog/placeholder.png';

    /**
     * @param $image
     * @throws \Exception
     */
    public function __construct($image)
    {
        $this->image = $image ?: self::PLACEHOLDER_IMAGE;
        $this->imagePath = public_path($this->image);
    }


    /**
     * 设置插件目录名称
     * @param $dirName
     * @return $this
     */
    public function setPluginDirName($dirName): static
    {
        $originImage = $this->image;
        if ($this->image == self::PLACEHOLDER_IMAGE) {
            return $this;
        }

        $this->imagePath = plugin_path("{$dirName}/Static") . $originImage;
        if (file_exists($this->imagePath)) {
            $this->image = strtolower('plugin/' . $dirName . $originImage);
        } else {
            $this->image = self::PLACEHOLDER_IMAGE;
            $this->imagePath = public_path($this->image);
        }

        return $this;
    }

    /**
     * 生成并获取缩略图
     * @param int $width
     * @param int $height
     * @return string
     */
    public function resize(int $width = 100, int $height = 100): string
    {
        try {
            $extension = pathinfo($this->imagePath, PATHINFO_EXTENSION);
            $newImage = 'cache/' . mb_substr($this->image, 0, mb_strrpos($this->image, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

            $newImagePath = public_path($newImage);
            if (!is_file($newImagePath) || (filemtime($this->imagePath) > filemtime($newImagePath))) {
                create_directories(dirname($newImage));
                $img = Image::make($this->imagePath);

                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $canvas = Image::canvas($width, $height);
                $canvas->insert($img, 'center');
                $canvas->save($newImagePath);
            }
            return asset($newImage);
        } catch (NotReadableException $e) {
            return $this->originUrl();
        }
    }


    /**
     * 获取原图地址
     */
    public function originUrl(): string
    {
        return asset($this->image);
    }
}
