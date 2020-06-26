<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTagsNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //make column 'tags' nullable in 'posts'
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('tags')->nullable()->change();
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
            $table->string('tags');
        });
    }
}
