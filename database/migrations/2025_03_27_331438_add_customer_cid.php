<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerCid extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('customers', 'cid')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('cid', 255)->nullable()->comment('APP 推送 ID')->after('id');
            });
        }
    }

    public function down()
    {

    }
}
