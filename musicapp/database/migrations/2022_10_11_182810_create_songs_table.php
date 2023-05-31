<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->nullable();
            $table->string('song_title')->nullable();
            $table->string('mp3_file')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->smallInteger('status')->default(1)->comment('1=yes; 0=no;');
            $table->smallInteger('is_deleted')->default(0)->comment('1=yes; 0=no;');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
