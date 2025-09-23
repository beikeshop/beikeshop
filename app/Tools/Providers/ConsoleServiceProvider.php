<?php

namespace App\Tools\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Beike\Tools\Commands;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands(config('plugins.commands', self::defaultCommands()->toArray()));
    }

    public function provides(): array
    {
        return self::defaultCommands()->toArray();
    }

    /**
     * Get the package default commands.
     *
     * @return Collection
     */
    public static function defaultCommands(): Collection
    {
        return collect([
            // Actions Commands
            //\App\Tools\Commands\Actions\CheckLangCommand::class,
            //\App\Tools\Commands\Actions\DisableCommand::class,
            //\App\Tools\Commands\Actions\EnableCommand::class,
            \App\Tools\Commands\Actions\ListCommand::class,
            //\App\Tools\Commands\Actions\ModelPruneCommand::class,
            \App\Tools\Commands\Actions\ModelShowCommand::class,
            \App\Tools\Commands\Actions\ModuleDeleteCommand::class,
            \App\Tools\Commands\Actions\UnUseCommand::class,
            \App\Tools\Commands\Actions\UseCommand::class,

            // Database Commands
            //\App\Tools\Commands\Database\MigrateCommand::class,
            //\App\Tools\Commands\Database\MigrateRefreshCommand::class,
            //\App\Tools\Commands\Database\MigrateResetCommand::class,
           // \App\Tools\Commands\Database\MigrateRollbackCommand::class,
            //\App\Tools\Commands\Database\MigrateStatusCommand::class,
            //\App\Tools\Commands\Database\SeedCommand::class,

            // Make Commands
            \App\Tools\Commands\Make\ActionMakeCommand::class,
            //\App\Tools\Commands\Make\CastMakeCommand::class,
            \App\Tools\Commands\Make\ChannelMakeCommand::class,
            \App\Tools\Commands\Make\CommandMakeCommand::class,
            \App\Tools\Commands\Make\ComponentClassMakeCommand::class,
            \App\Tools\Commands\Make\ComponentViewMakeCommand::class,
            \App\Tools\Commands\Make\ControllerMakeCommand::class,
            \App\Tools\Commands\Make\EventMakeCommand::class,
            \App\Tools\Commands\Make\EventProviderMakeCommand::class,
            \App\Tools\Commands\Make\EnumMakeCommand::class,
            \App\Tools\Commands\Make\ExceptionMakeCommand::class,
            \App\Tools\Commands\Make\FactoryMakeCommand::class,
            \App\Tools\Commands\Make\InterfaceMakeCommand::class,
            \App\Tools\Commands\Make\HelperMakeCommand::class,
            \App\Tools\Commands\Make\JobMakeCommand::class,
            \App\Tools\Commands\Make\ListenerMakeCommand::class,
            \App\Tools\Commands\Make\MailMakeCommand::class,
            \App\Tools\Commands\Make\MiddlewareMakeCommand::class,
            \App\Tools\Commands\Make\MigrationMakeCommand::class,
            \App\Tools\Commands\Make\ModelMakeCommand::class,
            \App\Tools\Commands\Make\ModuleMakeCommand::class,
            \App\Tools\Commands\Make\NotificationMakeCommand::class,
            \App\Tools\Commands\Make\ObserverMakeCommand::class,
            \App\Tools\Commands\Make\PolicyMakeCommand::class,
            \App\Tools\Commands\Make\ProviderMakeCommand::class,
            \App\Tools\Commands\Make\RequestMakeCommand::class,
            \App\Tools\Commands\Make\ResourceMakeCommand::class,
            //\App\Tools\Commands\Make\RouteProviderMakeCommand::class,
            //\App\Tools\Commands\Make\RuleMakeCommand::class,
            //\App\Tools\Commands\Make\ScopeMakeCommand::class,
            //\App\Tools\Commands\Make\SeedMakeCommand::class,
            \App\Tools\Commands\Make\ServiceMakeCommand::class,
            \App\Tools\Commands\Make\TraitMakeCommand::class,
            //\App\Tools\Commands\Make\TestMakeCommand::class,
            \App\Tools\Commands\Make\ViewMakeCommand::class,
            //\App\Tools\Commands\Make\MenuProviderMakeCommand::class,

            //Publish Commands
            //\App\Tools\Commands\Publish\PublishCommand::class,
            //\App\Tools\Commands\Publish\PublishConfigurationCommand::class,
           // \App\Tools\Commands\Publish\PublishMigrationCommand::class,
            //\App\Tools\Commands\Publish\PublishTranslationCommand::class,

            // Other Commands
            //\App\Tools\Commands\SetupCommand::class,

            //\App\Tools\Commands\Database\MigrateFreshCommand::class,

            //Custom

            \App\Tools\Commands\Custom\ZipCommand::class,
            \App\Tools\Commands\Make\AspectMakeCommand::class

        ]);
    }
}
