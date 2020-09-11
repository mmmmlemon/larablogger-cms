<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //create table 'categories'
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->integer('visual_order')->default(0)->nullable();
            $table->timestamps();
        });


        //default categories
        DB::table('categories')->insert(
            array(
                array(
                    'category_name' => 'blank',
                    'visual_order' => 0,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now()
                ),
                 array(
                    'category_name' => 'Videos',
                    'visual_order' => 1,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now()
                ),
                array(
                    'category_name' => 'Gallery',
                    'visual_order' => 2,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now()
                ),)
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
