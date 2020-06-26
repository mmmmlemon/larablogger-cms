<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSocialMediaBullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //make columns in 'social_media' table nullable
    public function up()
    {
        Schema::table('social_media', function($table)
        {
            $table->string('platform_name')->nullable()->change();
            $table->string('url')->nullable()->change();
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
}
