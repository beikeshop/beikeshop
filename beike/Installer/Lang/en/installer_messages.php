<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title' => 'Laravel Installer',
    'next' => 'Next Step',
    'back' => 'Previous',
    'finish' => 'Install',
    'environment' => 'Environmen',
    'status' => 'Status',
    'forms' => [
        'errorTitle' => 'The Following errors occurred:',
    ],

    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'title' => 'Welcome',
        'describe'   => 'Welcome to install BeikeShop. Easy Installation and Setup Wizard.',
        'message' => 'Easy Installation and Setup Wizard.',
        'next'    => 'Check Requirements',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Step 1 | Server Requirements',
        'environment' => 'Environmen',
        'title' => 'Server Requirements',
        'next'    => 'Check Permissions',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'title' => 'Directory permission detection',
        'next' => 'Configure Environment',
        'table' => 'Table of contents',
        'ask_permission' => '要求权限',
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'title' => 'System parameter configuration',
        'name_required' => 'An environment name is required.',
        'app_name_label' => 'App Name',
        'app_name_placeholder' => 'App Name',
        'app_environment_label' => 'App Environment',
        'app_environment_label_local' => 'Local',
        'app_environment_label_developement' => 'Development',
        'app_environment_label_qa' => 'Qa',
        'app_environment_label_production' => 'Production',
        'app_environment_label_other' => 'Other',
        'app_environment_placeholder_other' => 'Enter your environment...',
        'app_url_label' => 'App Url',
        'app_url_placeholder' => 'App Url',
        'db_connection_failed' => 'Could not connect to the database.',
        'db_connection_label' => 'Database Connection',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Database Host',
        'db_host_placeholder' => 'Database Host',
        'db_port_label' => 'Database Port',
        'db_port_placeholder' => 'Database Port',
        'db_name_label' => 'Database Name',
        'db_name_placeholder' => 'Database Name',
        'db_username_label' => 'Database User Name',
        'db_username_placeholder' => 'Database User Name',
        'db_password_label' => 'Database Password',
        'db_password_placeholder' => 'Database Password',
        'admin_email' => '后台账号',
        'admin_password' => '后台密码',
        'install' => 'Install',
    ],


    /*
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel Installer successfully INSTALLED on ',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installation Finished',
        'templateTitle' => 'Installation Finished',
        'migration' => 'Migration &amp; Seed Console Output:',
        'console' => 'Application Console Output:',
        'log' => 'Installation Log Entry:',
        'env' => 'Final .env File:',
        'exit' => 'Click here to exit',
        'finished' => 'Congratulations, the system is successfully installed, let\'s experience it now',
        'to_front' => 'Shop',
        'to_admin' => 'Admin Panel',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater' => [
        /*
         *
         * Shared translations.
         *
         */
        'title' => 'Laravel Updater',

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Welcome To The Updater',
            'message' => 'Welcome to the update wizard.',
        ],

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Overview',
            'message' => 'There is 1 update.|There are :number updates.',
            'install_updates' => 'Install Updates',
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Finished',
            'finished' => 'Application\'s database has been successfully updated.',
            'exit' => 'Click here to exit',
        ],

        'log' => [
            'success_message' => 'Laravel Installer successfully UPDATED on ',
        ],
    ],
];
