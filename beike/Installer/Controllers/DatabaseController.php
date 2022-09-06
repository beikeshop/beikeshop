<?php

namespace Beike\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Beike\Admin\Repositories\AdminUserRepo;
use Beike\Installer\Helpers\DatabaseManager;

class DatabaseController extends Controller
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
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        $rows = DB::select('SHOW TABLES');
        $tables = array_column($rows, 'Tables_in_' . env('DB_DATABASE'));
        foreach ($tables as $table) {
            Schema::drop($table);
        }
        DB::statement("SET FOREIGN_KEY_CHECKS = 1");

        try {
            $response = $this->databaseManager->migrateAndSeed();
        } catch (\Exception $e) {
            $params = request()->all();
            return redirect()->route('installer.environment')->withInput($params)->withErrors(['error' => $e->getMessage()]);
        }

        $email = request('admin_email');
        $data = [
            'name' => substr($email, 0, strpos($email, '@')),
            'email' => $email,
            'password' => request('admin_password'),
            'locale' => session('locale') ?? 'en',
            'active' => true,
        ];
        AdminUserRepo::createAdminUser($data);

        return redirect()->route('installer.final')->with(['message' => $response]);
    }
}
