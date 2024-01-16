<?php

namespace Beike\Installer\Controllers;

use Beike\Admin\Repositories\AdminUserRepo;
use Beike\Installer\Helpers\DatabaseManager;
use Illuminate\Http\RedirectResponse;
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $rows     = DB::select('SHOW TABLES');
        $database = config('database.connections.mysql.database');
        $tables   = array_column($rows, 'Tables_in_' . $database);
        foreach ($tables as $table) {
            Schema::drop($table);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $params = request()->all();

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
}
