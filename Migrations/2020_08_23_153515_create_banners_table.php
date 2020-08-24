<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('menu_id')->unsigned()->nullable()->default(null);
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

            $table->string('name');
            $table->string('image');
            $table->text('url')->nullable();
            $table->enum('status', array_keys(\Modules\Banners\Models\Banner::STATUS))->default('active');
            $table->enum('target', array_keys(\Modules\Banners\Models\Banner::TARGET))->default('_self');
            $table->integer('order')->default(1);
            $table->text('summary_01')->nullable();
            $table->text('summary_02')->nullable();

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
        Schema::dropIfExists('banners');
    }
}
