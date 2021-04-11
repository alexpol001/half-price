<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UwtInit extends Migration
{
    private function createCatalogTable() {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('catalog_id')->nullable();
            $table->string('slug');
            $table->string('title');

            $table->foreign('catalog_id')
                ->references('id')->on('catalogs')
                ->onDelete('cascade');
        });
    }

    private function createModelTable() {
        Schema::create('models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('catalog_id')->nullable();
            $table->string('slug');
            $table->string('title');

            $table->foreign('catalog_id')
                ->references('id')->on('catalogs')
                ->onDelete('cascade');
        });
    }

    private function createLinkTabTable() {
        Schema::create('link_tabs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('model_link_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->bigInteger('sort')->default(0);

            $table->foreign('model_id')
                ->references('id')->on('models')
                ->onDelete('cascade');

            $table->foreign('model_link_id')
                ->references('id')->on('models')
                ->onDelete('cascade');
        });
    }

    private function createFieldTable() {
        Schema::create('fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('link_tab_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->integer('type')->default(0);
            $table->integer('data_type')->default(0);
            $table->string('default_value')->nullable();
            $table->boolean('is_require')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_search')->default(false);
            $table->integer('compare_id')->nullable();
            $table->integer('length')->nullable();
            $table->boolean('is_trim')->nullable();
            $table->double('min')->nullable();
            $table->double('max')->nullable();
            $table->bigInteger('sort')->default(0);

            $table->foreign('link_tab_id')
                ->references('id')->on('link_tabs')
                ->onDelete('cascade');
        });

    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createCatalogTable();
        $this->createModelTable();
        $this->createLinkTabTable();
        $this->createFieldTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
        Schema::dropIfExists('link_tabs');
        Schema::dropIfExists('models');
        Schema::dropIfExists('catalogs');
    }
}
