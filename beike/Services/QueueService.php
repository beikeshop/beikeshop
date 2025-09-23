<?php

namespace Beike\Services;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class QueueService
{
    /**
     * 获取队列日志文件路径
     */
    protected static function getQueueLogPath(): string
    {
        $logPath = storage_path('logs/queue');
        if (! file_exists($logPath)) {
            mkdir($logPath, 0755, true);
        }

        return $logPath . '/queue-' . date('Y-m-d') . '.log';
    }

    /**
     * 写入日志
     */
    protected static function writeLog($message, $type = 'info'): void
    {
        $logFile    = self::getQueueLogPath();
        $dateTime   = date('Y-m-d H:i:s');
        $logMessage = "[{$dateTime}] [{$type}] {$message}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * 启动队列处理进程
     *
     * @param array $options 可选配置参数
     * @return bool
     */
    public static function startQueueWorker(array $options = []): bool
    {
        try {
            // 默认配置
            $defaultOptions = [
                'php_path'        => '/usr/bin/php',
                'timeout'         => null,
                'queue'           => 'default',
                'tries'           => 3,
                'memory'          => 256,
                'sleep'           => 3,
                'stop_when_empty' => true,
                'log_output'      => true,
                'output_file'     => self::getQueueLogPath(),
            ];

            // 合并配置
            $options = array_merge($defaultOptions, $options);

            // 构建命令
            $command = sprintf(
                '/usr/bin/nohup %s artisan queue:work %s %s %s %s %s >> %s 2>&1 &',
                $options['php_path'],
                $options['stop_when_empty'] ? '--stop-when-empty' : '',
                $options['queue'] ? "--queue={$options['queue']}" : '',
                $options['tries'] ? "--tries={$options['tries']}" : '',
                $options['memory'] ? "--memory={$options['memory']}" : '',
                $options['sleep'] ? "--sleep={$options['sleep']}" : '',
                $options['output_file']
            );

            self::writeLog("Starting queue worker with command: {$command}");

            // 创建进程
            $process = Process::fromShellCommandline($command);
            $process->setWorkingDirectory(base_path());

            // 设置超时
            if ($options['timeout'] !== null) {
                $process->setTimeout($options['timeout']);
            }

            // 启动进程
            $process->start();

            // 获取进程ID
            $pid = $process->getPid();
            self::writeLog("Queue worker started with PID: {$pid}");

            // 如果需要记录输出
            if ($options['log_output']) {
                $process->wait(function ($type, $buffer) {
                    if ($type === Process::ERR) {
                        self::writeLog($buffer, 'error');
                    } else {
                        self::writeLog($buffer);
                    }
                });
            }

            // 检查进程是否成功启动
            if (! $process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // 记录启动成功
            self::writeLog('Queue worker started successfully', 'success');

            return true;
        } catch (\Exception $e) {
            self::writeLog("Failed to start queue worker: {$e->getMessage()}", 'error');
            self::writeLog("Stack trace: {$e->getTraceAsString()}", 'error');

            return false;
        }
    }

    /**
     * 检查队列工作进程是否在运行
     *
     * @return bool
     */
    public static function isQueueWorkerRunning(): bool
    {
        try {
            $command = "ps aux | grep 'artisan queue:work' | grep -v grep";
            $process = Process::fromShellCommandline($command);
            $process->run();

            $isRunning = $process->isSuccessful() && ! empty($process->getOutput());
            self::writeLog(
                $isRunning ? 'Queue worker is running' : 'Queue worker is not running',
                $isRunning ? 'info' : 'warning'
            );

            return $isRunning;
        } catch (\Exception $e) {
            self::writeLog("Failed to check queue worker status: {$e->getMessage()}", 'error');

            return false;
        }
    }

    /**
     * 停止所有队列工作进程
     *
     * @return bool
     */
    public static function stopQueueWorkers(): bool
    {
        try {
            self::writeLog('Attempting to stop all queue workers');

            $command = "pkill -f 'artisan queue:work'";
            $process = Process::fromShellCommandline($command);
            $process->run();

            if ($process->isSuccessful()) {
                self::writeLog('Queue workers stopped successfully', 'success');

                return true;
            }
            self::writeLog('No queue workers were running to stop', 'warning');

            return false;

        } catch (\Exception $e) {
            self::writeLog("Failed to stop queue workers: {$e->getMessage()}", 'error');

            return false;
        }
    }

    /**
     * 重启队列工作进程
     *
     * @param array $options
     * @return bool
     */
    public static function restartQueueWorker(array $options = []): bool
    {
        self::writeLog('Attempting to restart queue worker');

        self::stopQueueWorkers();
        sleep(2); // 等待进程完全停止

        $result = self::startQueueWorker($options);

        if ($result) {
            self::writeLog('Queue worker restarted successfully', 'success');
        } else {
            self::writeLog('Failed to restart queue worker', 'error');
        }

        return $result;
    }

    /**
     * 获取队列状态信息
     *
     * @return array
     */
    public static function getQueueStatus(): array
    {
        try {
            $command = "ps aux | grep 'artisan queue:work' | grep -v grep";
            $process = Process::fromShellCommandline($command);
            $process->run();

            $output    = $process->getOutput();
            $processes = array_filter(explode("\n", $output));

            $status = [
                'running'       => ! empty($processes),
                'process_count' => count($processes),
                'processes'     => [],
            ];

            foreach ($processes as $processInfo) {
                $parts                 = preg_split('/\s+/', trim($processInfo));
                $status['processes'][] = [
                    'user'    => $parts[0],
                    'pid'     => $parts[1],
                    'cpu'     => $parts[2],
                    'memory'  => $parts[3],
                    'started' => $parts[8],
                ];
            }

            self::writeLog('Queue status checked: ' . json_encode($status));

            return $status;
        } catch (\Exception $e) {
            self::writeLog("Failed to get queue status: {$e->getMessage()}", 'error');

            return ['running' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * 更新 .env 文件中的值
     *
     * @param array $values
     * @return bool
     */
    public static function updateEnv(array $values): bool
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            // 转义特殊字符
            $value = str_replace('"', '\"', $value);

            // 检查是否存在该键
            if (preg_match("/^{$key}=/m", $content)) {
                // 更新现有值
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $content
                );
            } else {
                // 添加新值
                $content .= PHP_EOL . "{$key}=\"{$value}\"";
            }
        }

        return file_put_contents($envPath, $content) !== false;
    }
}
