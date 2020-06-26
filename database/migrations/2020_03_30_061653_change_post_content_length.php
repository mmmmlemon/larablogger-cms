<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePostContentLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //change the length of column 'post_content' in 'posts'
    public function up()
    {
        Schema::table('posts', function ($table) {
            $table->string('post_content', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function ($table) {
            $table->string('post_content', 255)->change();
        });
    }
}
