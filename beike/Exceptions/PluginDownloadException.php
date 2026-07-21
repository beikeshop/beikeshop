<?php

namespace Beike\Exceptions;

use Exception;
use Throwable;

/**
 * 插件下载异常类
 *
 * 提供结构化的异常信息，包含上下文数据用于日志记录和错误追踪
 *
 * @author Beike Team
 * @version 1.0.0
 */
class PluginDownloadException extends Exception
{
    /**
     * 异常上下文数据
     *
     * @var array
     */
    protected array $context = [];

    /**
     * 构造函数
     *
     * @param string         $message  异常消息
     * @param array          $context  上下文数据
     * @param int            $code     异常代码
     * @param Throwable|null $previous 前一个异常
     */
    public function __construct(
        string $message = '',
        array $context = [],
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    /**
     * 获取异常上下文数据
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * 设置上下文数据
     *
     * @param array $context
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * 添加上下文数据
     *
     * @param string $key
     * @param mixed  $value
     * @return self
     */
    public function addContext(string $key, $value): self
    {
        $this->context[$key] = $value;

        return $this;
    }

    /**
     * 获取格式化的异常信息
     *
     * @return string
     */
    public function getFormattedMessage(): string
    {
        $message = $this->getMessage();

        if (! empty($this->context)) {
            $contextStr = json_encode($this->context, JSON_UNESCAPED_UNICODE);
            $message .= " | Context: {$contextStr}";
        }

        return $message;
    }
}
