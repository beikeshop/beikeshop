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
    'status' => '状态',


    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'template_title' => '欢迎',
        'title' => '欢迎来到安装引导程序',
        'describe' => '欢迎使用安装引导，在后面的步骤中我们将检测您的系统环境和安装条件是否达标，请根据每一步中的提示信息操作，谢谢。',
        'message' => '欢迎来到安装向导.',
        'next' => '检测系统环境',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'template_title' => '第一步 | 服务器环境',
        'title' => '系统环境要求检测',
        'environment' => '环境',
        'next' => '检测权限',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'template_title' => '第二步 | 目录权限',
        'title' => '目录权限检测',
        'table' => '目录',
        'next' => '配置环境参数',
    ],

    /*
    *
    * Environment page translations.
    *
    */
    'environment' => [
        'template_title' => '第三步 | 系统参数',
        'title' => '系统参数配置',
        'app_url_label' => '您的应用URL',
        'database_link' => '数据库链接',
        'admin_info' => '设置后台账号密码',
        'app_url_placeholder' => '输入您的应用URL',
        'db_connection_failed' => '无法连接到数据库！',
        'db_connection_label' => '数据库类型',
        'db_connection_failed_host_port' => '数据库主机或端口错误！',
        'db_connection_failed_user_password' => '数据库账号或密码错误！',
        'db_connection_failed_database_name' => '数据库名不存在！',
        'db_connection_failed_invalid_version' => '数据库版本必须大于5.7！',
        'db_connection_label_mysql' => 'MySQL',
        'db_connection_label_sqlite' => 'SQLite',
        'db_connection_label_pgsql' => 'PostgreSQL',
        'db_connection_label_sqlsrv' => 'SQL Server',
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
        'admin_email' => '后台账号',
        'admin_password' => '后台密码',
        'install' => '安装',
        'ajax_database_parameters' => '检测数据库参数...',
        'ajax_database_success' => '数据库连接成功',
        'error_email' => '请填写正确的邮箱地址',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'template_title' => '安装完成',
        'title' => '获取安装结果',
        'finished' => '恭喜您，系统安装成功，赶快体验吧',
        'to_front' => '访问前台',
        'to_admin' => '访问后台',
    ],
];
