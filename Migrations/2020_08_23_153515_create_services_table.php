<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('menu_id')->unsigned()->nullable()->default(null);
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

            $table->bigInteger('parent_id')->unsigned()->nullable()->default(null);
            $table->foreign('parent_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique()->nullable();  
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
            $table->string('icon')->nullable();          
            $table->longText('summary')->nullable();
            $table->enum('status', array_keys(\Modules\Services\Models\Service::STATUS))->default('active');
            $table->enum('featured', array_keys(\Modules\Services\Models\Service::FEATURED))->default('active');
            $table->integer('order')->default(1);
            $table->longText('body')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
