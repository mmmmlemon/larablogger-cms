<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTagsToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //add column 'tags' to table 'posts'
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('tags');
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
            $table->dropColumn('tags');
        });
    }
}
