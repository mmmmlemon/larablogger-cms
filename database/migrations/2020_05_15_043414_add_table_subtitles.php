<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableSubtitles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->id();
            $table->string('display_name', 20);
            $table->string('actual_name',50);
            $table->unsignedBigInteger('media_id');
            $table->integer('visibility');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtitles');
    }
}
