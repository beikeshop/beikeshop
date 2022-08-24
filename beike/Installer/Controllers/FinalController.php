<?php

namespace Beike\Installer\Controllers;

use Illuminate\Routing\Controller;
use Beike\Installer\Helpers\EnvironmentManager;
use Beike\Installer\Helpers\FinalInstallManager;
use Beike\Installer\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \Beike\Installer\Helpers\InstalledFileManager $fileManager
     * @param \Beike\Installer\Helpers\FinalInstallManager $finalInstall
     * @param \Beike\Installer\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        $steps = 5;

        return view('installer::finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile', 'steps'));
    }
}
