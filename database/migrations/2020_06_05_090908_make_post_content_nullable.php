<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePostContentNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //make column 'post_content' nullable
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->longtext('post_content')->nullable()->change();
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
            $table->longtext('post_content')->change();
        });
    }
}
