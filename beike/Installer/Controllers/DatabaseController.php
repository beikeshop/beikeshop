<?php

namespace Beike\Installer\Controllers;

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

        return redirect()->route('installer.final')
                         ->with(['message' => $response]);
    }
}
