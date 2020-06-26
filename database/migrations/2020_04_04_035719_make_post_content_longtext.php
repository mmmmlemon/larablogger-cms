<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePostContentLongtext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //change datatype of column 'post_content' to longText in 'post'
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->longtext('post_content')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('post_content')->change();
        });
    }
}
