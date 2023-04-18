<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('published_at')->nullable();
            $table->text('content');
            $table->string('image')->nullable();
            $table->string('slug')->unique();
            $table->unsignedInteger('category_id');
            $table->tinyInteger('visibility')->default(1);
            $table->tinyInteger('pin')->default(0);

            $table->index(["user_id"], 'fk_articles_users_idx');
            $table->index(["category_id"], 'fk_articles_categories_idx');

            $table->foreign('user_id', 'fk_articles_users_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('category_id', 'fk_articles_categories_idx')
                ->references('id')->on('categories')
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
        Schema::dropIfExists('articles');
    }
}
