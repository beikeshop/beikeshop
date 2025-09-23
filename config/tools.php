<?php

use App\Tools\Activators\FileActivator;
use App\Tools\Providers\ConsoleServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Plugin',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs' => [
        'enabled' => false,
        'path'    => base_path('beike/Tools/Commands/stubs'),
        'files'   => [
            //'routes/api'   => 'routes/api.php',
            //'routes/admin' => 'routes/admin.php',
            //'views/index' => 'resources/views/index.blade.php',
            //'views/master' => 'resources/views/layouts/master.blade.php',
            //'vite' => 'vite.config.js',
            //'package' => 'package.json',

            //'routes/web'   => 'routes/web.php',
            //'scaffold/config' => 'config/config.php',
            //'assets/js/app'   => 'resources/assets/js/app.js',
            //'assets/sass/app' => 'resources/assets/sass/app.scss',

            //custom

            'custom/lang' => [
                'Lang/zh_cn/common.php',
                'Lang/en/common.php',
            ],

            //   'custom/lang'      => 'Lang/zh_cn/common.php',
            // 'custom/lang'      => 'Lang/zh_cn/common.php',
            'custom/view'      => 'Views/shop/index.blade.php',
            //'custom/form'      => 'resources/views/form/form.blade.php',
            'custom/bootstrap'    => 'Bootstrap.php',
            'custom/config'       => 'config.json',
            'custom/shop'         => 'Routes/shop.php',
            'custom/columns'      => 'columns.php',
        ],
        'replacements' => [
            'routes/web'       => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/api'       => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/admin'     => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'vite'             => ['LOWER_NAME', 'STUDLY_NAME'],
            'json'             => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/index'      => ['LOWER_NAME'],
            'views/master'     => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config'  => ['STUDLY_NAME'],
            'composer'         => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
                'APP_FOLDER_NAME',
            ],

            //custom
            'custom/lang'       => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'custom/view'       => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'custom/bootstrap'  => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE', 'AUTHOR_NAME', 'AUTHOR_EMAIL'],
            'custom/config'     => ['LOWER_NAME', 'STUDLY_NAME', 'VENDOR', 'AUTHOR_EMAIL', 'PLUGIN_CODE'],
            'custom/shop'       => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],

        ],
        'gitkeep' => false,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Plugins path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'plugins' => base_path('plugins'),

        /*
        |--------------------------------------------------------------------------
        | Plugins assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the plugins' assets path.
        |
        */
        'assets' => public_path('plugins'),

        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => base_path('Migrations'),

        /*
        |--------------------------------------------------------------------------
        | The app path
        |--------------------------------------------------------------------------
        |
        | app folder name
        | for example can change it to 'src' or 'App'
        */
        'app_folder' => 'app/',

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator' => [
            // app/
            'actions'         => ['path' => 'Actions', 'generate' => false],
            'casts'           => ['path' => 'Casts', 'generate' => false],
            'channels'        => ['path' => 'Broadcasting', 'generate' => false],
            'command'         => ['path' => 'Console', 'generate' => false],
            'component-class' => ['path' => 'Views/Components', 'generate' => false],
            'emails'          => ['path' => 'Emails', 'generate' => false],
            'event'           => ['path' => 'Events', 'generate' => false],
            'enums'           => ['path' => 'Enums', 'generate' => false],
            'exceptions'      => ['path' => 'Exceptions', 'generate' => false],
            'jobs'            => ['path' => 'Jobs', 'generate' => false],
            'helpers'         => ['path' => 'Helpers', 'generate' => false],
            'interfaces'      => ['path' => 'Interfaces', 'generate' => false],
            'listener'        => ['path' => 'Listeners', 'generate' => false],
            'notifications'   => ['path' => 'Notifications', 'generate' => false],
            'observer'        => ['path' => 'Observers', 'generate' => false],
            'policies'        => ['path' => 'Policies', 'generate' => false],
            'rules'           => ['path' => 'Rules', 'generate' => false],
            'scopes'          => ['path' => 'Models/Scopes', 'generate' => false],
            'traits'          => ['path' => 'Traits', 'generate' => false],

            'repository'      => ['path' => 'Models/Repositories', 'generate' => false],
            'resource'        => ['path' => 'Models/Resources', 'generate' => false],
            'route-provider'  => ['path' => 'Providers', 'generate' => false],
            'services'        => ['path' => 'Services', 'generate' => false],
            'model'           => ['path' => 'Models', 'generate' => true],
            'provider'        => ['path' => 'Providers', 'generate' => false],
            // app/Http/
            'controller'      => ['path' => 'Controllers', 'generate' => true],
            'route-model'     => ['path' => 'Models', 'generate' => true],
            'filter'          => ['path' => 'Middleware', 'generate' => false],
            'request'         => ['path' => 'Requests', 'generate' => false],

            // config/
            'config'          => ['path' => 'config', 'generate' => false],

            // database/
            'factory'         => ['path' => 'database/factories', 'generate' => false],
            'migration'       => ['path' => 'Migrations', 'generate' => true],
            'seeder'          => ['path' => 'database/seeders', 'generate' => false],

            // lang/
            'lang'            => ['path' => 'Lang', 'generate' => true],

            // resource/
            'assets'          => ['path' => 'resources/assets', 'generate' => false],
            'component-view'  => ['path' => 'resources/views/components', 'generate' => false],
            'views'           => ['path' => 'resources/views', 'generate' => false],

            // routes/
            'routes'          => ['path' => 'routes', 'generate' => false],

            // tests/
            'test-feature'    => ['path' => 'tests/Feature', 'generate' => false],
            'test-unit'       => ['path' => 'tests/Unit', 'generate' => false],

            //custom
            'test-unit'               => ['path' => 'tests/Unit', 'generate' => false],
            'plugin-view'             => ['path' => 'Views/shop', 'generate' => true],
            'plugin-routes'           => ['path' => 'Routes',     'generate' => true],
            'plugin-html-aspect'      => ['path' => 'Aspect/Html', 'generate' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. You can add your own commands to merge section.
    |
    */
    'commands' => ConsoleServiceProvider::defaultCommands()
        ->merge([
            // New commands go here
        ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan' => [
        'enabled' => false,
        'paths'   => [
            base_path('vendor/*/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file, generated by this package
    |
    */
    'composer' => [
        'vendor' => env('PLUGIN_VENDOR', '成都光大网络科技有限公司'),
        'author' => [
            'name'  => env('PLUGIN_AUTHOR_NAME', ''),
            'email' => env('PLUGIN_AUTHOR_EMAIL', 'guangda.work'),
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up the caching feature.
    |
    */
    'cache' => [
        'enabled'  => env('PLUGINS_CACHE_ENABLED', false),
        'driver'   => env('PLUGINS_CACHE_DRIVER', 'file'),
        'key'      => env('PLUGINS_CACHE_KEY', 'laravel-plugins'),
        'lifetime' => env('PLUGINS_CACHE_LIFETIME', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-plugins will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database, etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_plugins
    */
    'activators' => [
        'file' => [
            'class'          => FileActivator::class,
            'statuses-file'  => storage_path('app/plugins/plugins.json'),
            'cache-key'      => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    'activator' => 'file',

    'zip' => [
        'ignore_dir' => ['.git', '.idea'],
    ],
];
