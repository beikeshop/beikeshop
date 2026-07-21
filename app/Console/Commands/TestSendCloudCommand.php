<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class TestSendCloudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-sendcloud 
                            {--to= : 收件人邮箱地址}
                            {--api-user= : SendCloud API 用户名}
                            {--api-key= : SendCloud API 密钥}
                            {--dry-run : 仅显示配置，不实际发送}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试 SendCloud 邮件发送功能';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== SendCloud 邮件发送测试 ===');
        $this->newLine();

        // 获取参数
        $to      = $this->option('to') ?: $this->ask('请输入收件人邮箱地址');
        $apiUser = $this->option('api-user') ?: $this->ask('请输入 SendCloud API 用户名');
        $apiKey  = $this->option('api-key') ?: $this->secret('请输入 SendCloud API 密钥');
        $dryRun  = $this->option('dry-run');

        if (! $to || ! $apiUser || ! $apiKey) {
            $this->error('缺少必要参数！');

            return 1;
        }

        // 验证邮箱格式
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error('无效的邮箱地址格式！');

            return 1;
        }

        // 配置 SendCloud
        $this->configureMailer($apiUser, $apiKey);

        // 显示配置信息
        $this->displayConfiguration($to, $apiUser, $apiKey);

        if ($dryRun) {
            $this->warn('干运行模式：不会实际发送邮件');

            return 0;
        }

        // 确认发送
        if (! $this->confirm('确定要发送测试邮件吗？')) {
            $this->info('已取消发送');

            return 0;
        }

        // 发送测试邮件
        return $this->sendTestEmail($to);
    }

    /**
     * 配置 SendCloud 邮件驱动
     */
    private function configureMailer(string $apiUser, string $apiKey): void
    {
        Config::set('mail.default', 'sendcloud');
        Config::set('mail.mailers.sendcloud', [
            'transport' => 'sendcloud',
            'api_user'  => $apiUser,
            'api_key'   => $apiKey,
            'endpoint'  => 'https://api.sendcloud.net',
        ]);
        Config::set('mail.from.address', 'test@example.com');
        Config::set('mail.from.name', 'SendCloud Test');
    }

    /**
     * 显示配置信息
     */
    private function displayConfiguration(string $to, string $apiUser, string $apiKey): void
    {
        $this->info('📋 配置信息:');
        $this->table(
            ['配置项', '值'],
            [
                ['收件人', $to],
                ['API 用户名', $apiUser],
                ['API 密钥', str_repeat('*', strlen($apiKey) - 4) . substr($apiKey, -4)],
                ['API 端点', 'https://api.sendcloud.net'],
                ['发件人', config('mail.from.address')],
                ['发件人名称', config('mail.from.name')],
            ]
        );
        $this->newLine();
    }

    /**
     * 发送测试邮件
     */
    private function sendTestEmail(string $to): int
    {
        try {
            $this->info('📤 正在发送测试邮件...');

            $testMail = new TestSendCloudMail;

            Mail::to($to)->send($testMail);

            $this->info('✅ 邮件发送成功！');
            $this->info('📬 请检查收件箱（包括垃圾邮件文件夹）');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ 邮件发送失败：' . $e->getMessage());

            $this->newLine();
            $this->warn('🔧 故障排除建议：');
            $this->line('1. 检查 API 凭据是否正确');
            $this->line('2. 确认发件人地址已在 SendCloud 验证');
            $this->line('3. 检查网络连接');
            $this->line('4. 查看详细日志：storage/logs/laravel.log');

            return 1;
        }
    }
}

/**
 * 测试邮件类
 */
class TestSendCloudMail extends Mailable
{
    use SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SendCloud 测试邮件 - ' . now()->format('Y-m-d H:i:s'))
            ->view('emails.test-sendcloud')
            ->with([
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'server'    => request()->getHost(),
            ]);
    }
}
