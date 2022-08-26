<?php

namespace Beike\Installer\Controllers;

use Beike\Admin\Repositories\AdminUserRepo;
use Illuminate\Routing\Controller;
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $response = $this->databaseManager->migrateAndSeed();

        $email = request('admin_email');
        $data = [
            'name' => substr($email, 0, strpos($email, '@')),
            'email' => $email,
            'password' => request('admin_password'),
            'locale' => 'en',
            'active' => true,
        ];
        AdminUserRepo::createAdminUser($data);

        return redirect()->route('installer.final')
                         ->with(['message' => $response]);
    }
}
