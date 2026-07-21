<?php

namespace Plugin\GdMigrateImagePaths;

class Bootstrap
{
    public function boot(): void
    {
        add_hook_blade('admin.plugin.form', function ($callback, $output, $data) {
            $code = $data['plugin']->code;
            if ($code == 'gd_migrate_image_paths') {
                return view('GdMigrateImagePaths::admin.index', $data)->render();
            }

            return $output;
        }, 1);
    }
}
