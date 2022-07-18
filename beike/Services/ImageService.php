<?php
/**
 * ImageService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
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
     * @param string $image
     * @throws \Exception
     */
    public function __construct(string $image)
    {
        $this->image = $image ?: self::PLACEHOLDER_IMAGE;
        $imagePath = public_path($this->image);
        $this->imagePath = $imagePath;
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
