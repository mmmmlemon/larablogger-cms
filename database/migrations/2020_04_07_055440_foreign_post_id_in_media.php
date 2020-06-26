<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignPostIdInMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //add foreign key on column 'post_id' in 'media'
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
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
            //
        });
    }
}
