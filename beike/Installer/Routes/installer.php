<?php

use Beike\Installer\Controllers\DatabaseController;
use Beike\Installer\Controllers\EnvironmentController;
use Beike\Installer\Controllers\FinalController;
use Beike\Installer\Controllers\PermissionsController;
use Beike\Installer\Controllers\RequirementsController;
use Beike\Installer\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')
    ->name('installer.')
    ->middleware(\App\Http\Middleware\SetLocaleInstaller::class)
    ->group(function () {
        Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
        Route::get('requirements', [RequirementsController::class, 'index'])->name('requirements');
        Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions');
        Route::middleware(['installer'])
            ->group(function () {
                Route::get('lang/{lang}', [WelcomeController::class, 'locale'])->name('lang.switch');
                Route::get('environment', [EnvironmentController::class, 'index'])->name('environment');
                Route::post('environment/save', [EnvironmentController::class, 'saveWizard'])->name('environment.save');
                Route::post('environment/validate_db', [EnvironmentController::class, 'validateDatabase'])->name('environment.validate_db');
                Route::get('database', [DatabaseController::class, 'index'])->name('database');
                Route::get('final', [FinalController::class, 'index'])->name('final');
            });

    });
