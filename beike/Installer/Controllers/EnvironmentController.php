<?php

namespace Beike\Installer\Controllers;

use Beike\Installer\Helpers\EnvironmentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnvironmentController extends BaseController
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->checkInstalled();
        $steps = 4;

        return view('installer::environment-wizard', compact('steps'));
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request    $request
     * @param Redirector $redirect
     * @return RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect): RedirectResponse
    {
        $this->checkInstalled();
        $rules    = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer::installer_messages.environment.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('installer.environment')->withInput()->withErrors($validator->errors());
        }

        if ($this->checkDatabaseConnection($request) !== true) {
            return $redirect->route('installer.environment')->withInput()->withErrors([
                'database_connection' => trans('installer::installer_messages.environment.db_connection_failed'),
            ]);
        }

        $this->EnvironmentManager->saveFileWizard($request);

        $params = $request->all();

        return redirect(route('installer.database', $params));
    }

    /**
     * 数据库信息检测
     *
     * @param Request $request
     * @return JsonResponse|array
     */
    public function validateDatabase(Request $request): JsonResponse|array
    {
        $this->checkInstalled();
        $rules    = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer::installer_messages.environment.name_required'),
        ];

        unset($rules['admin_email'], $rules['admin_password']);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return json_fail('', $validator->errors());
        }

        $dbValidateResult = $this->checkDatabaseConnection($request);
        if ($dbValidateResult !== true) {
            return json_fail('', $dbValidateResult);
        }

        return json_success('');
    }

    /**
     * TODO: We can remove this code if PR will be merged: https://github.com/RachidLaasri/LaravelInstaller/pull/162
     * Validate database connection with user credentials (Form Wizard).
     *
     * @param Request $request
     * @return array|bool
     */
    private function checkDatabaseConnection(Request $request): bool|array
    {
        $this->checkInstalled();
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default'     => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver'   => $connection,
                        'host'     => $request->input('database_hostname'),
                        'port'     => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                        'options'  => [
                            \PDO::ATTR_TIMEOUT => 1,
                        ],
                    ]),
                ],
            ],
        ]);

        DB::purge();

        $result = [];

        try {
            $pdo           = DB::connection()->getPdo();
            $serverVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
            if (version_compare($serverVersion, '5.7', '<')) {
                $result['database_version'] = trans('installer::installer_messages.environment.db_connection_failed_invalid_version');

                return $result;
            }

            return true;
        } catch (\PDOException $e) {
            switch ($e->getCode()) {
                case 1115:
                    $result['database_version'] = trans('installer::installer_messages.environment.db_connection_failed_invalid_version');

                    break;
                case 2002:
                    $result['database_hostname'] = trans('installer::installer_messages.environment.db_connection_failed_host_port');
                    $result['database_port']     = trans('installer::installer_messages.environment.db_connection_failed_host_port');

                    break;
                case 1045:
                    $result['database_username'] = trans('installer::installer_messages.environment.db_connection_failed_user_password');
                    $result['database_password'] = trans('installer::installer_messages.environment.db_connection_failed_user_password');

                    break;
                case 1049:
                    $result['database_name'] = trans('installer::installer_messages.environment.db_connection_failed_database_name');

                    break;
                default:
                    $result['database_other'] = $e->getMessage();
            }
        }

        return $result;
    }
}
