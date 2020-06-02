<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //настройки сайта
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("site_title"); //заголовок сайта
            $table->string("site_subtitle"); //подзаголовок сайта
            $table->timestamps();
        });

        //опции по умолчанию
        DB::table('settings')->insert(
            array(
                'site_title' => 'Web-site Title',
                'site_subtitle' => 'some random text',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
