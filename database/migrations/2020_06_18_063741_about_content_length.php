<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AboutContentLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //change length of column 'about_content'
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('about_content',5000)->change();
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
            $table->boolean('about_content',2000)->change();
        });
    }
}
