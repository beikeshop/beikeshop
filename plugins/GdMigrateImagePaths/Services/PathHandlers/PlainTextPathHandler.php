<?php

/**
 * 纯文本路径处理器
 *
 * 处理纯文本字段中的图片路径转换
 * 将 catalog/ 转换为 image/catalog/
 */

namespace Plugin\GdMigrateImagePaths\Services\PathHandlers;

class PlainTextPathHandler implements PathHandlerInterface
{
    /**
     * 检查是否可以处理该内容
     *
     * @param string $content 字段内容
     * @return bool
     */
    public function canHandle(string $content): bool
    {
        // 纯文本处理器可以处理任何内容
        // 但优先级最低，应该最后尝试
        return true;
    }

    /**
     * 转换路径
     *
     * @param string $content 原始内容
     * @return string 转换后的内容
     */
    public function transform(string $content): string
    {
        // 空字符串直接返回
        if (empty($content)) {
            return $content;
        }

        // 检查是否已经是新格式（幂等性）
        if (str_contains($content, 'image/catalog/')) {
            return $content;
        }

        // 将 catalog/ 替换为 image/catalog/
        // 使用正则确保只替换路径开头或 / 后面的 catalog/
        $transformed = preg_replace(
            '#(^|/)catalog/#',
            '$1image/catalog/',
            $content
        );

        return $transformed;
    }

    /**
     * 检查路径是否需要转换
     *
     * @param string $content 内容
     * @return bool
     */
    public function needsTransform(string $content): bool
    {
        return ! empty($content)
            && str_contains($content, 'catalog/')
            && ! str_contains($content, 'image/catalog/');
    }
}
