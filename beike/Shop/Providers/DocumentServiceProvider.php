<?php

namespace Beike\Shop\Providers;

use Beike\Services\AssetService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AssetService::class, function () {
            return new AssetService;
        });
    }

    public function boot(): void
    {
        Blade::directive('addStyle', function ($expression) {
            return '<?php app(' . AssetService::class . "::class)->addStyle(...[$expression]); ?>";
        });

        Blade::directive('addScript', function ($expression) {
            return '<?php app(' . AssetService::class . "::class)->addScript(...[$expression]); ?>";
        });

        Blade::directive('renderStyles', function () {
            return '<?php foreach(app(' . AssetService::class . '::class)->getStyles() as $style): ?>' .
                   '<link rel="stylesheet" href="<?php echo $style; ?>">' .
                   '<?php endforeach; ?>';
        });

        Blade::directive('renderScripts', function () {
            return '<?php foreach(app(' . AssetService::class . '::class)->getScripts() as $script): ?>' .
                   "<script src=\"<?php echo \$script['path']; ?>\"<?php foreach(\$script['attrs'] as \$attr => \$val) if(\$val) echo ' ' . \$attr; ?>></script>" .
                   '<?php endforeach; ?>';
        });
    }
}
