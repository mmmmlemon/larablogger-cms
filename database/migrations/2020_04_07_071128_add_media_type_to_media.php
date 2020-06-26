<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaTypeToMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //add column 'media_type' to 'media'
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->enum("media_type",["video","image"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn("media_type");
        });
    }
}
