<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->tinyInteger('visibility')->default(1);
            $table->tinyInteger('pin')->default(0);
            $table->nullableTimestamps();
        });

        //create entertainment,sports and health categories
        DB::table('categories')->insert([
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'visibility' => 1, 'pin' => 1],
            ['name' => 'Sports', 'slug' => 'sports', 'visibility' => 1, 'pin' => 1],
            ['name' => 'Health', 'slug' => 'health', 'visibility' => 1, 'pin' => 1],
            ['name' => 'Politics', 'slug' => 'politics', 'visibility' => 1, 'pin' => 1],
            ['name' => 'Other', 'slug' => 'other', 'visibility' => 1, 'pin' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
