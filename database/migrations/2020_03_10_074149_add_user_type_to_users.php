<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTypeToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //adds column 'user_type' to 'users' table
    //User types
    //0 - Super Admin
    //1 - Admin
    //2 - User
    public function up()
    {   
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default(2); //default user type is 'User'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type']);
        });
    }
}
