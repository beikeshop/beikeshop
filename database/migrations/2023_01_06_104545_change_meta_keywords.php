<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_descriptions', function (Blueprint $table) {
            $table->renameColumn('meta_keyword', 'meta_keywords');
        });
        Schema::table('page_descriptions', function (Blueprint $table) {
            $table->renameColumn('meta_keyword', 'meta_keywords');
        });
        Schema::table('product_descriptions', function (Blueprint $table) {
            $table->renameColumn('meta_keyword', 'meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
