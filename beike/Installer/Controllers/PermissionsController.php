<?php

namespace Beike\Installer\Controllers;

use Beike\Installer\Helpers\PermissionsChecker;
use Illuminate\Routing\Controller;

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
        if (installed()) {
            exit('Already installed');
        }
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
