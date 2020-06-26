<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //add columns to table 'media'
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('display_name');
            $table->string('actual_name');
            $table->integer('visibility')->default(1);
            $table->string('thumbnail_url')->nullable();
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
            $table->dropColumn('display_name');
            $table->dropColumn('actual_name');
            $table->dropColumn('visibility');
            $table->dropColumn('thumbnail_url');
        });
    }
}
