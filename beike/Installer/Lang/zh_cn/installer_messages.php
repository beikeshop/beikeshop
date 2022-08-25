<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title' => 'Laravel安装程序',
    'next' => '下一步',
    'finish' => '安装',

    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'title'   => '欢迎来到Laravel安装程序',
        'message' => '欢迎来到安装向导.',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'title' => '环境要求',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'title' => '权限',
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'wizard' => [
            'form' => [
                'app_url_label' => '您的应用URL',
                'app_url_placeholder' => '输入您的应用URL',
                'db_connection_failed' => '无法连接到数据库！',
                'db_connection_label' => '数据库连接',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => '数据库主机',
                'db_host_placeholder' => '输入数据库主机ip或url',
                'db_port_label' => '数据库端口',
                'db_port_placeholder' => '输入数据库端口',
                'db_name_label' => '数据库名',
                'db_name_placeholder' => '输入数据库名',
                'db_username_label' => '数据库账号',
                'db_username_placeholder' => '输入数据库账号',
                'db_password_label' => '数据库账号密码',
                'db_password_placeholder' => '输入数据库账号密码',

                'buttons' => [
                    'install' => '安装',
                ],
            ],
        ],
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => '完成',
        'finished' => '应用已成功安装.',
        'exit' => '点击退出',
    ],
];
