<?php

namespace Tests\Data\Catalog;

class LoginPage
{
    public const Login = [
        'login_url' => '/login',
        //                #page-login > div.login-wrap > div:nth-child(1) > form > div.card-body.px-md-2 > div:nth-child(1) > div > div > input
        'login_email' => '#page-login > div.login-wrap > div:nth-child(1) > form > div.card-body.px-md-2 > div:nth-child(1) > div > div > input',
        'login_pwd'   => '#page-login > div.login-wrap > div:nth-child(1) > form > div.card-body.px-md-2 > div:nth-child(2) > div > div > input',
        'login_btn'   => '.btn.btn-dark.btn-lg.w-100.fw-bold:first-of-type',
        'login_text'  => 'Home',
    ];

    public const Register = [
        //                   #page-login > div.login-wrap > div:nth-child(3) > div.card-body.px-md-2 > form > div:nth-child(1) > div > div > input
        'register_email'  => '#page-login > div.login-wrap > div:nth-child(3) > div.card-body.px-md-2 > form > div:nth-child(1) > div > div > input',
        'register_pwd'    => '#page-login > div.login-wrap > div:nth-child(3) > div.card-body.px-md-2 > form > div:nth-child(2) > div > div > input',
        'register_re_pwd' => '#page-login > div.login-wrap > div:nth-child(3) > div.card-body.px-md-2 > form > div:nth-child(3) > div > div > input',
        //    "register_btn"=> ".btn.btn-dark.btn-lg.w-100.fw-bold:nth-child(2)",
        'register_btn'  => 'Register',
        'register_text' => 'Home',
    ];

    public const Iframe = [
        'iframe_name' => '#layui-layer-iframe1',
    ];
}
