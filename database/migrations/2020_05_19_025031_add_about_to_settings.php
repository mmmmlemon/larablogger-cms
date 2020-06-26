<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAboutToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //add column 'about_content' to settings
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('about_content',2000)->default("Any info about this web-site goes here.");
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
            $table->dropColumn('about_content');
        });
    }
}
