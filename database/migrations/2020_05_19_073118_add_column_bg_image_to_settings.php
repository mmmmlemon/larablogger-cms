<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBgImageToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //add column 'bg_image' to 'settings'
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('bg_image', 300)->default('/images/bg/bg_01.jpg');
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
            $table->dropColumn("bg_image");
        });
    }
}
