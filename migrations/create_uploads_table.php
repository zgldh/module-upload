<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
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
            $table->string('name')->defalut('');
            $table->string('description')->default('');
            $table->string('disk')->default('');
            $table->string('path')->default('');
            $table->integer('size')->default(0);
            $table->string('type')->default('');
            $table->integer('user_id')->default(0)->index();
            $table->integer('uploadable_id')->nullable();
            $table->string('uploadable_type')->nullable();

            $table->timestamps();

            $table->index([
                'uploadable_id',
                'uploadable_type'
            ], 'uploadable_index');
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
