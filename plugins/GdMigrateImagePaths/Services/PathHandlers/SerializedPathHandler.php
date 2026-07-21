<?php

/**
 * PHP 序列化数据路径处理器
 *
 * 处理 PHP 序列化格式字段中的图片路径转换
 * 递归遍历序列化数据结构，转换所有路径字符串
 */

namespace Plugin\GdMigrateImagePaths\Services\PathHandlers;

class SerializedPathHandler implements PathHandlerInterface
{
    private PlainTextPathHandler $plainHandler;

    public function __construct()
    {
        $this->plainHandler = new PlainTextPathHandler;
    }

    /**
     * 检查是否可以处理该内容
     *
     * @param string $content 字段内容
     * @return bool
     */
    public function canHandle(string $content): bool
    {
        if (empty($content)) {
            return false;
        }

        // 检查是否是序列化数据格式
        // 序列化数据通常以 a:, s:, O:, i:, b:, d: 等开头
        if (! preg_match('/^[aOsibdr]:[0-9]+:/', $content)) {
            return false;
        }

        // 尝试反序列化
        $result = @unserialize($content);

        // 特殊处理 false 值（b:0; 是合法的序列化数据）
        return $result !== false || $content === 'b:0;';
    }

    /**
     * 转换路径
     *
     * @param string $content 原始序列化内容
     * @return string 转换后的序列化内容
     * @throws \RuntimeException
     */
    public function transform(string $content): string
    {
        if (empty($content)) {
            return $content;
        }

        // 反序列化
        $data = @unserialize($content);

        if ($data === false && $content !== 'b:0;') {
            throw new \RuntimeException('Invalid serialized data');
        }

        // 递归转换所有路径
        $transformed = $this->recursiveTransform($data);

        // 重新序列化
        return serialize($transformed);
    }

    /**
     * 检查路径是否需要转换
     *
     * @param string $content 内容
     * @return bool
     */
    public function needsTransform(string $content): bool
    {
        return $this->canHandle($content)
            && str_contains($content, 'catalog/')
            && ! str_contains($content, 'image/catalog/');
    }

    /**
     * 递归转换数据结构中的所有路径
     *
     * @param mixed $data 数据
     * @return mixed 转换后的数据
     */
    private function recursiveTransform($data)
    {
        if (is_string($data)) {
            // 如果是字符串，尝试转换路径
            return $this->plainHandler->transform($data);
        }

        if (is_array($data)) {
            // 如果是数组，递归处理每个元素
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->recursiveTransform($value);
            }

            return $result;
        }

        if (is_object($data)) {
            // 如果是对象，递归处理每个属性
            foreach ($data as $key => $value) {
                $data->$key = $this->recursiveTransform($value);
            }
        }

        // 其他类型（数字、布尔值、null）直接返回
        return $data;
    }
}
