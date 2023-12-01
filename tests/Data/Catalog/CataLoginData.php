<?php

namespace Tests\Data\Catalog;

class CataLoginData
{
    public const True_Login = [
        'email'    => 'test@163.com',
        'password' => '123456',
        'assert'   => 'Sign Out',
    ];

    public const False_Login = [
        'false_email'    => 'test1@163.com',
        'illegal_email'  => 'test',
        'false_password' => '1234567',
        'false_assert'   => 'User login and registration',
        'illegal_assert' => 'Please enter a valid email address!',

    ];
}
