<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('page_descriptions', function (Blueprint $table) {
            $table->string('summary', 500)->change();
        });
    }

    public function down(): void
    {
        //
    }
};
