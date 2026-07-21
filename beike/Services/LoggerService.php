<?php

namespace Beike\Services;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class LoggerService
{
    protected string $filename;

    protected Logger $logger;

    public function __construct(string $filename)
    {
        // 自动拼接日期
        if (! str_ends_with($filename, '.log')) {
            $filename = date('Y-m-d') . '-' . $filename . '.log';
        }

        $this->filename = $filename;
        $this->logger   = $this->buildLogger();
    }

    protected function buildLogger(): Logger
    {
        $logger = new Logger('Beike');

        $filePath = storage_path('logs/' . $this->filename);
        $stream   = new StreamHandler($filePath, Level::Debug); // 所有级别都记录

        // 自定义日志格式
        $output    = "[%datetime%] %channel%.%level_name%: %message% %context% %extra.file%:%extra.line%\n";
        $formatter = new LineFormatter($output, 'Y-m-d H:i:s', true, true);
        $stream->setFormatter($formatter);
        $logger->pushHandler($stream);

        // 注入源文件 + 行号，跳过自身
        $logger->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, [
            self::class,
        ]));

        return $logger;
    }

    public function write(string $message, string $level = 'info', array $context = []): void
    {
        $levelEnum = $this->parseLevel($level);
        $this->logger->log($levelEnum, $message, $context);
    }

    public function debug(string $msg, array $ctx = []): void
    {
        $this->write($msg, 'debug', $ctx);
    }

    public function info(string $msg, array $ctx = []): void
    {
        $this->write($msg, 'info', $ctx);
    }

    public function warning(string $msg, array $ctx = []): void
    {
        $this->write($msg, 'warning', $ctx);
    }

    public function error(string $msg, array $ctx = []): void
    {
        $this->write($msg, 'error', $ctx);
    }

    protected function parseLevel(string $level): Level
    {
        $levels = [
            'debug'     => Level::Debug,
            'info'      => Level::Info,
            'notice'    => Level::Notice,
            'warning'   => Level::Warning,
            'error'     => Level::Error,
            'critical'  => Level::Critical,
            'alert'     => Level::Alert,
            'emergency' => Level::Emergency,
        ];

        return $levels[strtolower($level)] ?? Level::Info;
    }
}
