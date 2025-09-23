<?php

namespace App\Tools\Commands\Actions;

use App\Tools\Commands\BaseCommand;
use Illuminate\Support\Facades\File;
use Beike\Repositories\PluginRepo;
use Illuminate\Support\Str;
class ModuleDeleteCommand extends BaseCommand
{
    protected $name        = 'plugin:delete';
    protected $description = 'Delete a plugin from the application';

    public function executeAction($name): void
    {
        $this->uninstall($name);
        $module = $this->getModuleModel($name);
        $this->components->task("Deleting <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($name, $module) {
            $module->delete();
            File::delete(storage_path('app/plugins/'.strtolower($module->getName()).'.json'));
        });
    }

    public function getInfo(): string|null
    {
        return 'deleting plugin ...';
    }

    /**
     *  uninstall plugin
     *
     * @param $code
     * @return void
     */
    public function uninstall($code): void
    {
        try {
            $code = Str::snake($code);
            $plugin = app('plugin')->getPluginOrFail($code);
            PluginRepo::uninstallPlugin($plugin);
        } catch (\Exception $e) {
            $this->components->error("Plugin [{$code}] uninstall fail: {$e->getMessage()}");
        }
    }
}
