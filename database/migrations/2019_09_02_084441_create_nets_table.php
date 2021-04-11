<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->double('sort');
            $table->unsignedBigInteger('logo_crop_image_id')->nullable();

            $table->foreign('logo_crop_image_id')
                ->references('id')->on('crop_images')
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
        Schema::dropIfExists('nets');
    }
}
