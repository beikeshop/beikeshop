<?php

namespace Beike\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * BeikeShop Installation Command
 * 
 * Handles database migration, seeding, and admin user creation.
 */
class BeikeShopInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beikeshop:install
                            {--domain=localhost : Website domain}
                            {--db-host=localhost : Database host}
                            {--db-port=3306 : Database port}
                            {--db-name=beikeshop : Database name}
                            {--db-user=beikeshop : Database user}
                            {--db-password= : Database password}
                            {--admin-email= : Admin email}
                            {--admin-password= : Admin password}
                            {--admin-name=admin : Admin name}
                            {--force : Force installation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install BeikeShop e-commerce system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║     BeikeShop Installation Command       ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        // Check if already installed
        if ($this->isInstalled() && !$this->option('force')) {
            $this->error('BeikeShop is already installed. Use --force to reinstall.');
            return 1;
        }

        // Step 1: Configure environment
        $this->task('Configuring environment', function () {
            $this->configureEnvironment();
        });

        // Step 2: Test database connection
        $this->task('Testing database connection', function () {
            $this->testDatabaseConnection();
        });

        // Step 3: Run migrations
        $this->task('Running database migrations', function () {
            Artisan::call('migrate:fresh', ['--force' => true]);
        });

        // Step 4: Seed database
        $this->task('Seeding database', function () {
            Artisan::call('db:seed', ['--force' => true]);
        });

        // Step 5: Create admin user
        $this->task('Creating admin user', function () {
            $this->createAdminUser();
        });

        // Step 6: Generate app key
        $this->task('Generating application key', function () {
            Artisan::call('key:generate', ['--force' => true]);
        });

        // Step 7: Clear caches
        $this->task('Clearing caches', function () {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
        });

        // Step 8: Mark installation complete
        $this->task('Marking installation complete', function () {
            $this->markInstalled();
        });

        $this->newLine();
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║       Installation Completed!            ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        $this->displayResult();

        return 0;
    }

    /**
     * Check if BeikeShop is already installed
     */
    protected function isInstalled(): bool
    {
        return file_exists(storage_path('installed'));
    }

    /**
     * Run a step with a short status message.
     */
    protected function task(string $description, callable $callback): void
    {
        $this->line("→ {$description}...");
        $callback();
        $this->info("✓ {$description}");
    }

    /**
     * Configure environment variables
     */
    protected function configureEnvironment(): void
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $env = file_get_contents($envPath);
        
        // Update database configuration
        $env = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $this->stringOption('db-host'), $env);
        $env = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $this->stringOption('db-port'), $env);
        $env = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $this->stringOption('db-name'), $env);
        $env = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $this->stringOption('db-user'), $env);
        $env = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $this->stringOption('db-password'), $env);
        
        // Update app URL
        $domain = $this->stringOption('domain');
        $appUrl = str_starts_with($domain, 'http') ? $domain : "http://{$domain}";
        $env = preg_replace('/APP_URL=.*/', 'APP_URL=' . $appUrl, $env);

        file_put_contents($envPath, $env);

        Config::set('database.connections.mysql.host', $this->stringOption('db-host'));
        Config::set('database.connections.mysql.port', $this->stringOption('db-port'));
        Config::set('database.connections.mysql.database', $this->stringOption('db-name'));
        Config::set('database.connections.mysql.username', $this->stringOption('db-user'));
        Config::set('database.connections.mysql.password', $this->stringOption('db-password'));
        DB::purge('mysql');
    }

    /**
     * Test database connection
     */
    protected function testDatabaseConnection(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Create admin user
     */
    protected function createAdminUser(): void
    {
        // Check if admin table exists
        if (!Schema::hasTable('admin_users')) {
            return;
        }

        // Delete existing admin with same email
        DB::table('admin_users')->where('email', $this->stringOption('admin-email'))->delete();

        // Create new admin user
        DB::table('admin_users')->insert([
            'name' => $this->stringOption('admin-name'),
            'email' => $this->stringOption('admin-email'),
            'password' => Hash::make($this->stringOption('admin-password')),
            'locale' => 'zh_cn',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Mark installation as complete
     */
    protected function markInstalled(): void
    {
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
    }

    /**
     * Display installation result
     */
    protected function displayResult(): void
    {
        $domain = $this->stringOption('domain');
        $appUrl = str_starts_with($domain, 'http') ? $domain : "http://{$domain}";

        $this->table(
            ['Item', 'Value'],
            [
                ['Website URL', $appUrl],
                ['Admin URL', $appUrl . '/admin'],
                ['Admin Email', $this->stringOption('admin-email')],
                ['Admin Password', $this->stringOption('admin-password')],
                ['Database Name', $this->stringOption('db-name')],
                ['Database User', $this->stringOption('db-user')],
            ]
        );

        $this->newLine();
        $this->warn('⚠️  Security: Change admin password immediately in production!');
    }

    protected function stringOption(string $name): string
    {
        $value = $this->option($name);

        return is_array($value) ? implode('', $value) : (string) $value;
    }
}
