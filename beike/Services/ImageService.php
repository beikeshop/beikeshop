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
class ImageService
{

    public function resize($image, $width = 100, $height = 100)
    {
        $imagePath = public_path($image);
        $img = \Intervention\Image\Facades\Image::make($imagePath);
        $img->resize($width, $height);
        // $img->insert('public/watermark.png');

        $baseName = basename($imagePath);
        $imageInfo = explode('.', $image);
        $imageName = $imageInfo[0];
        $imageExt = $imageInfo[1];
        $newImagePath = public_path("cache/{$imageName}-{$width}x{$height}.{$imageExt}");
        \Illuminate\Support\Facades\File::ensureDirectoryExists($newImagePath, 0755, true);
        $img->save($newImagePath);
        dd($newImagePath);
    }
}
