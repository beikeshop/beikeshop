<?php

namespace Beike\Installer\Controllers;

use Beike\Admin\Repositories\AdminUserRepo;
use Beike\Installer\Helpers\DatabaseManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends BaseController
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return RedirectResponse
     */
    public function index()
    {
        $this->checkInstalled();
        $params = request()->all();
//        if ($params['database_connection'] != 'pgsql'){
//            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
//            $rows     = DB::select('SHOW TABLES');
//            $database = config('database.connections.mysql.database');
//            $tables   = array_column($rows, 'Tables_in_' . $database);
//            foreach ($tables as $table) {
//                Schema::drop($table);
//            }
//            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
//        }
//
//        if ($params['database_connection'] == 'pgsql'){
//            if ($this->getTableSum() > 0)
//            {
//                return redirect()->route('installer.environment')->withInput($params)->withErrors(['error' =>__('installer::installer_messages.environment.table_already_exists')]);
//            }
//        }

        try {
            $response = $this->databaseManager->migrateAndSeed();
            $status   = $response['status']  ?? '';
            $message  = $response['message'] ?? '';
            if ($status == 'error' && $message) {
                return redirect()->route('installer.environment')->withInput($params)->withErrors(['error' => $message]);
            }
        } catch (\Exception $e) {
            return redirect()->route('installer.environment')->withInput($params)->withErrors(['error' => $e->getMessage()]);
        }

        if ($params['database_connection'] == 'pgsql'){
            //fix sequence
            Artisan::call('postgreSQL:sequence');
        }

        $email = request('admin_email');
        $data  = [
            'name'     => substr($email, 0, strpos($email, '@')),
            'email'    => $email,
            'password' => request('admin_password'),
            'locale'   => session('locale') ?? 'zh_cn',
            'active'   => true,
        ];

        AdminUserRepo::createAdminUser($data);

        return redirect()->route('installer.final', request()->only('admin_email', 'admin_password'))->with(['message' => $response]);
    }

    private function getTableSum()
    {
        $results = DB::selectOne("SELECT COUNT(*) as sum FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema';");
        return $results->sum;
    }
}
