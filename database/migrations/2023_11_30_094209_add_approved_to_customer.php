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
        if (!Schema::hasColumn('customers', 'active')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->renameColumn('status', 'active');
            });

            Schema::table('customers', function (Blueprint $table) {
                $table->string('status')->comment('审核状态')->default('approved')->after('avatar');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customers', 'active')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->removeColumn('status');
            });

            Schema::table('customers', function (Blueprint $table) {
                $table->renameColumn('active', 'status');
            });
        }
    }
};
