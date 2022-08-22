<?php

namespace Beike\Installer\Controllers;

use Illuminate\Routing\Controller;
use Beike\Installer\Helpers\RequirementsChecker;

class RequirementsController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @param RequirementsChecker $checker
     */
    public function __construct(RequirementsChecker $checker)
    {
        $this->requirements = $checker;
    }

    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        $steps = 2;

        return view('installer::requirements', compact('requirements', 'phpSupportInfo', 'steps'));
    }
}
