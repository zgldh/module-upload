<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTableZgldh extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('disk');
            $table->string('path');
            $table->integer('size');
            $table->integer('user_id')->nullable();
            $table->integer('uploadable_id')->nullable();
            $table->string('uploadable_type')->nullable();

            $table->timestamps();

            $table->index([
                'uploadable_id',
                'uploadable_type'
            ],'uploadable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('uploads');
    }

}
