<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFromToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //add column 'from_email' to 'settings'
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('from_email')->default('from@example.com');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('from_email');
        });
    }
}
