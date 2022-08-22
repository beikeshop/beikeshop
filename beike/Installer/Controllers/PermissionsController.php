<?php

namespace Beike\Installer\Controllers;

use Illuminate\Routing\Controller;
use Beike\Installer\Helpers\PermissionsChecker;

class PermissionsController extends Controller
{
    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @param PermissionsChecker $checker
     */
    public function __construct(PermissionsChecker $checker)
    {
        $this->permissions = $checker;
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $permissions = $this->permissions->check(
            config('installer.permissions')
        );

        $steps = 3;

        return view('installer::permissions', compact('permissions', 'steps'));
    }
}
