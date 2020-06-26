<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDisplayNameInSubtitles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //change lengths of column 'display_name'
    public function up()
    {
        Schema::table('subtitles', function (Blueprint $table) {
            $table->string('display_name',100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subtitles', function (Blueprint $table) {
            $table->string('display_name',20)->change();
        });
    }
}
