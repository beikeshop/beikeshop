<?php
/**
 * ChangeRootPassword.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-13 20:56:16
 * @modified   2023-02-13 20:56:16
 */

namespace Beike\Console\Commands;

use Beike\Models\AdminUser;
use Illuminate\Console\Command;

class ChangeRootPassword extends Command
{
    protected $signature = 'root:password';

    protected $description = '修改后台Root账号(第一个管理员)';

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $user        = AdminUser::query()->first();
        $newPassword = $this->ask("请为管理员 {$user->email} 设置新密码");

        if (! $newPassword) {
            $this->info('请输入新密码');

            return;
        }

        $user->password = bcrypt($newPassword);
        $user->saveOrFail();
        $this->info('管理员密码设置成功!');
    }
}
