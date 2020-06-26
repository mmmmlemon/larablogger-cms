<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //social_media table
    public function up()
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('platform_name')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        //добавляем три соц. сети по умолчанию и одно пустое поле
        DB::table('social_media')->insert(
            array(
                ['platform_name' => 'YouTube',
                'url' => 'https://youtube.com',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()],
                ['platform_name' => 'Twitter',
                'url' => 'https://twitter.com',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()],
                ['platform_name' => 'Instagram',
                'url' => 'https://instagram.com',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()],
                ['platform_name' => null,
                'url' => null,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()],
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
        Schema::dropIfExists('social_media');
    }
}
