<?php

/**
 * 路径处理器接口
 *
 * 定义所有路径处理器必须实现的方法
 */

namespace Plugin\GdMigrateImagePaths\Services\PathHandlers;

interface PathHandlerInterface
{
    /**
     * 检查是否可以处理该内容
     *
     * @param string $content 字段内容
     * @return bool
     */
    public function canHandle(string $content): bool;

    /**
     * 转换路径
     *
     * @param string $content 原始内容
     * @return string 转换后的内容
     */
    public function transform(string $content): string;

    /**
     * 检查路径是否需要转换
     *
     * @param string $content 内容
     * @return bool
     */
    public function needsTransform(string $content): bool;
}
