<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('net_id')->nullable();
            $table->unsignedBigInteger('user_info_id');

            $table->string('city');
            $table->string('street');
            $table->string('house');
            $table->string('phone');
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();

            $table->foreign('user_info_id')
                ->references('id')->on('user_infos')
                ->onDelete('cascade');

            $table->foreign('net_id')
                ->references('id')->on('nets')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
