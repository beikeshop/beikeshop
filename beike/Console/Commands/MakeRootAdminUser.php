<?php

namespace Beike\Console\Commands;

use Beike\Models\AdminUser;
use Illuminate\Console\Command;

class MakeRootAdminUser extends Command
{
    protected $signature = 'make:admin';

    protected $description = '生成第 1 个 root admin 账号';

    public function handle()
    {
        $email    = $this->ask('请输入登录邮箱地址');
        $password = $this->ask('请输入密码');

        if (! $email || ! $password) {
            $this->info('邮箱地址/手机号码不能为空，退出');

            return;
        }

        $admin = AdminUser::create([
            'name'     => 'John Doe',
            'email'    => $email,
            'password' => bcrypt($password),
            'active'   => true,
        ]);

        $this->info('账号创建成功，退出');
    }
}
