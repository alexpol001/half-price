<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('product_measure_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->double('measure_value');
            $table->string('code');
            $table->unsignedBigInteger('logo_crop_image_id')->nullable();

            $table->foreign('product_category_id')
                ->references('id')->on('product_categories')
                ->onDelete('cascade');

            $table->foreign('product_measure_id')
                ->references('id')->on('product_measures')
                ->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
