<?php

namespace App\Providers;

use App\Rewrite\SmartTranslator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class SmartTranslationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 在 boot 阶段替换 translator
        $this->app->extend('translator', function (Translator $translator, $app) {
            return new SmartTranslator(
                $translator->getLoader(),
                $translator->getLocale()
            );
        });
    }
}
