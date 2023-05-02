<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lyrics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('song')->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('artist_id');
            $table->string('slug')->nullable();
            $table->string('album')->nullable();
            $table->string('audio_link')->nullable();
            $table->boolean('pin')->default(0);
            $table->string('video_link')->nullable();
            $table->tinyInteger('visibility')->default(1);

            $table->index(["artist_id"], 'fk_lyrics_artists1_idx');

            $table->foreign('artist_id', 'fk_lyrics_artists1_idx')
                ->references('id')->on('artists')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lyrics');
    }
}
