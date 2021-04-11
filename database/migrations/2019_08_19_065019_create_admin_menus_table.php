<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_menu_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('title');
            $table->string('path')->nullable();
            $table->string('icon')->nullable();
            $table->double('sort')->default(0);

            $table->foreign('admin_menu_id')
                ->references('id')->on('admin_menus')
                ->onDelete('cascade');
        });

        Schema::create('admin_menu_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_menu_id');
            $table->unsignedBigInteger('model_id');

            $table->foreign('admin_menu_id')
                ->references('id')->on('admin_menus')
                ->onDelete('cascade');

            $table->foreign('model_id')
                ->references('id')->on('models')
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
        Schema::dropIfExists('admin_menu_models');
        Schema::dropIfExists('admin_menus');
    }
}
