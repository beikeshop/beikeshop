<?php

use Illuminate\Support\Facades\Route;
use Plugin\GdMigrateImagePaths\Controllers\MigrationController;

Route::group([
    'prefix'     => 'migration',
    'middleware' => ['admin'],
], function () {
    // 扫描数据库
    Route::post('/scan', [MigrationController::class, 'scan'])->name('migration.scan');

    // 预览迁移
    Route::post('/preview', [MigrationController::class, 'preview'])->name('migration.preview');

    // 执行迁移
    Route::post('/execute', [MigrationController::class, 'execute'])->name('migration.execute');

    // 验证迁移结果
    Route::post('/verify', [MigrationController::class, 'verify'])->name('migration.verify');

    // 导出报告
    Route::get('/export/{reportId}', [MigrationController::class, 'exportReport'])->name('migration.export');
});
