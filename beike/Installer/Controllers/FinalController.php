<?php

namespace Beike\Installer\Controllers;

use Beike\Installer\Helpers\EnvironmentManager;
use Beike\Installer\Helpers\FinalInstallManager;
use Beike\Installer\Helpers\InstalledFileManager;

class FinalController extends BaseController
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @param FinalInstallManager  $finalInstall
     * @param EnvironmentManager   $environment
     * @return mixed
     */
    public function index(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $this->checkInstalled();

        $finalMessages      = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile       = $environment->getEnvContent();

        $steps = 5;

        $data                   = compact('finalMessages', 'finalStatusMessage', 'finalEnvFile', 'steps');
        $data['admin_email']    = request('admin_email');
        $data['admin_password'] = request('admin_password');

        return view('installer::finished', $data);
    }
}
