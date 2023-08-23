<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title'        => 'Laravel Installer',
    'next'         => 'Next Step',
    'back'         => 'Previous',
    'finish'       => 'Install',
    'status'       => 'Status',
    'forms'        => [
        'errorTitle' => 'The Following errors occurred:',
    ],

    /*
     *
     * Home page translations.
     *
     */
    'welcome'      => [
        'template_title'   => 'Welcome',
        'title'            => 'Welcome',
        'describe'         => 'Welcome to install BeikeShop. Easy Installation and Setup Wizard.',
        'message'          => 'Easy Installation and Setup Wizard.',
        'next'             => 'Check Requirements',
        'copyright_btn'    => 'I have read the agreement and agree',
        'copyright_title'  => 'Copyright Description',
        'copyright_list_1' => '1. The copyright of this system belongs to Chengdu GuangDa Network Technology Co., Ltd.',
        'copyright_list_2' => '2. Any individual or organization can not be allowed to sell or lease this system and its derivatives without our explicit written authorization.',
        'copyright_list_3' => '3. Please keep the copyright information, and please contact us if you wanna remove it.',
        'statement_1'      => 'Disclaimer:',
        'statement_2'      => 'Risk statement: The use and installation of the BeikeShop system is based on your own decision. We are not responsible for any losses, damages, or legal liabilities that may arise from the use of this system',
        'statement_3'      => 'Legal compliance: When using this website system, you agree not to engage in any illegal, infringing or violating local laws and regulations in business activities. We do not assume any responsibility for your business behavior',
        'statement_4'      => 'Data loss and hacker attacks: Although we have taken reasonable security measures to protect the data and security of the system, the risk of information transmission loss and hacker attacks still exists. We are not responsible for data loss, hacker attacks, or other security incidents in the system. You are responsible for taking measures to protect data and system security on your own',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'template_title' => 'Step 1 | Server Requirements',
        'environment'    => 'Environmen',
        'title'          => 'Server Requirements',
        'next'           => 'Check Permissions',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions'  => [
        'template_title' => 'Step 2 | Directory Permission',
        'title'          => 'Directory permission detection',
        'next'           => 'Configure Environment',
        'table'          => 'Table of contents',
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment'  => [
        'template_title'                       => 'Step 3 | System Parameters',
        'title'                                => 'System parameter configuration',
        'name_required'                        => 'An environment name is required.',
        'database_link'                        => 'Database link',
        'admin_info'                           => 'Set admin account password',
        'app_name_label'                       => 'App Name',
        'app_name_placeholder'                 => 'App Name',
        'app_environment_label'                => 'App Environment',
        'db_connection_failed_host_port'       => 'Database host or port error!',
        'db_connection_failed_user_password'   => 'Database username or password error!',
        'db_connection_failed_database_name'   => 'Database name not exist!',
        'db_connection_failed_invalid_version' => 'MySQL version must grater than 5.7!',
        'app_environment_label_local'          => 'Local',
        'app_environment_label_developement'   => 'Development',
        'app_environment_label_qa'             => 'Qa',
        'app_environment_label_production'     => 'Production',
        'app_environment_label_other'          => 'Other',
        'app_environment_placeholder_other'    => 'Enter your environment...',
        'app_url_label'                        => 'App Url',
        'app_url_placeholder'                  => 'App Url',
        'db_connection_failed'                 => 'Could not connect to the database.',
        'db_connection_label'                  => 'Database Connection',
        'db_connection_label_mysql'            => 'mysql',
        'db_connection_label_sqlite'           => 'sqlite',
        'db_connection_label_pgsql'            => 'pgsql',
        'db_connection_label_sqlsrv'           => 'sqlsrv',
        'db_host_label'                        => 'Database Host',
        'db_host_placeholder'                  => 'Database Host',
        'db_port_label'                        => 'Database Port',
        'db_port_placeholder'                  => 'Database Port',
        'db_name_label'                        => 'Database Name',
        'db_name_placeholder'                  => 'Database Name',
        'db_username_label'                    => 'Database User Name',
        'db_username_placeholder'              => 'Database User Name',
        'db_password_label'                    => 'Database Password',
        'db_password_placeholder'              => 'Database Password',
        'admin_email'                          => 'Admin User Account',
        'admin_password'                       => 'Admin Password',
        'install'                              => 'Install',
        'ajax_database_parameters'             => 'Check database parameters...',
        'ajax_database_success'                => 'Database connection succeeded',
        'error_email'                          => 'Please fill in the correct email address',
    ],

    /*
     *
     * Installed Log translations.
     *
     */
    'installed'    => [
        'success_log_message' => 'Laravel Installer successfully INSTALLED on ',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final'        => [
        'title'          => 'Installation Finished',
        'template_title' => 'Installation Finished',
        'migration'      => 'Migration &amp; Seed Console Output:',
        'console'        => 'Application Console Output:',
        'log'            => 'Installation Log Entry:',
        'env'            => 'Final .env File:',
        'exit'           => 'Click here to exit',
        'finished'       => 'Congratulations, the system is successfully installed, let\'s experience it now',
        'to_front'       => 'Shop',
        'to_admin'       => 'Admin Panel',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater'      => [
        /*
         *
         * Shared translations.
         *
         */
        'title'    => 'Laravel Updater',

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome'  => [
            'title'   => 'Welcome To The Updater',
            'message' => 'Welcome to the update wizard.',
        ],

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'           => 'Overview',
            'message'         => 'There is 1 update.|There are :number updates.',
            'install_updates' => 'Install Updates',
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final'    => [
            'title'    => 'Finished',
            'finished' => 'Application\'s database has been successfully updated.',
            'exit'     => 'Click here to exit',
        ],

        'log'      => [
            'success_message' => 'Laravel Installer successfully UPDATED on ',
        ],
    ],
];
