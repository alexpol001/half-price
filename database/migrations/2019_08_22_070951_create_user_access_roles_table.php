<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_role_id');
            $table->unsignedBigInteger('user_role_id_belong');

            $table->foreign('user_role_id')
                ->references('id')->on('user_roles')
                ->onDelete('cascade');

            $table->foreign('user_role_id_belong')
                ->references('id')->on('user_roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_access_roles');
    }
}
