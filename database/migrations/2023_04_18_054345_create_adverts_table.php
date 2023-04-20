<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('type', ['vertical', 'horizontal']);
            $table->string('link')->nullable();
            $table->tinyInteger('visibility')->default(1);
            $table->decimal('price', 13, 2)->nullable();
            $table->string('image')->nullable();
            $table->nullableTimestamps();
        });

        DB::table('adverts')->insert([
            'title' => 'Advert 1',
            'description' => 'Advert 1 description',
            'type' => 'vertical',
            'link' => 'https://www.google.com',
            'price' => 100.00,
            'image' => 'https://via.placeholder.com/640x480.png/00ddee?text=image1',
        ]);

        DB::table('adverts')->insert([
            'title' => 'Advert 2',
            'description' => 'Advert 2 description',
            'type' => 'horizontal',
            'link' => 'https://www.google.com',
            'price' => 200.00,
            'image' => 'https://via.placeholder.com/640x480.png/00dded?text=image2',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
    }
}
