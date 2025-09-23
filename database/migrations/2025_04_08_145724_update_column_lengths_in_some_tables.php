<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'category_descriptions',
            'page_descriptions',
            'product_descriptions',
            'page_category_descriptions',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if ($tableName == 'page_descriptions' || $tableName == 'page_category_descriptions') {
                    $table->string('title', 255)->change();
                } else {
                    $table->string('name', 255)->change();
                }
                $table->string('meta_title', 255)->change();
                $table->string('meta_description', 500)->change();
                $table->string('meta_keywords', 255)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
