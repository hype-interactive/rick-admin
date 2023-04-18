<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('tag_id');

            $table->index(["article_id"], 'fk_article_tags_articles_idx');
            $table->index(["tag_id"], 'fk_article_tags_tags_idx');

            $table->foreign('article_id', 'fk_article_tags_articles_idx')
                ->references('id')->on('articles')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tag_id', 'fk_article_tags_tags_idx')
                ->references('id')->on('tags')
                ->onDelete('no action')
                ->onUpdate('no action');
                
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
        Schema::dropIfExists('article_tags');
    }
}
