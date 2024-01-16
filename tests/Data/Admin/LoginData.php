<?php

namespace Tests\Data\Admin;

class LoginData
{
    public const Ture_Data = [
        'email'    => 'root@guangda.work',
        'password' => '123456',
        'assert'   => '后台管理',

    ];

    public const False_Data = [
        'false_email'    => 'test1@163.com',
        'illegal_email'  => 'test',
        'false_password' => '1234567',
        'false_assert'   => '账号密码不匹配',
        'illegal_assert' => 'email 必须是一个有效的电子邮件地址。',
        'no_email'       => '请输入 email',
        'no_pwd'         => '请输入 password',
    ];
}
