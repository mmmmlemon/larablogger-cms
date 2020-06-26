<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactEmailToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //add column 'contact_email' to settings
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('contact_email')->default('example@yoursite.com');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('contact_email');
        });
    }
}
