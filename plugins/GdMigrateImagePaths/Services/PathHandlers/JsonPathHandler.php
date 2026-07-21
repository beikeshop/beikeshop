<?php

/**
 * JSON 路径处理器
 *
 * 处理 JSON 格式字段中的图片路径转换
 * 递归遍历 JSON 结构，转换所有路径字符串
 */

namespace Plugin\GdMigrateImagePaths\Services\PathHandlers;

class JsonPathHandler implements PathHandlerInterface
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

        // 尝试解析 JSON
        json_decode($content);

        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * 转换路径
     *
     * @param string $content 原始 JSON 内容
     * @return string 转换后的 JSON 内容
     * @throws \JsonException
     */
    public function transform(string $content): string
    {
        if (empty($content)) {
            return $content;
        }

        // 解析 JSON
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \JsonException('Invalid JSON: ' . json_last_error_msg());
        }

        // 递归转换所有路径
        $transformed = $this->recursiveTransform($data);

        // 重新编码为 JSON，保持格式
        return json_encode(
            $transformed,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
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
            return array_map([$this, 'recursiveTransform'], $data);
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
