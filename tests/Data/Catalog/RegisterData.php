<?php

namespace Tests\Data\Catalog;

class RegisterData
{
    public const True_Register = [
        'email'    => 'test2@163.com',
        'password' => '123456',
        'assert'   => 'Sign Out',
    ];

    public const False_Register = [
        'exist_email'    => 'test@163.com',  //已注册的email
        'illegal_email'  => 'test',
        'false_password' => '1234567',
        'false_assert'   => 'User login and registration',
        'illegal_assert' => 'Please enter a valid email address!',

    ];
}
