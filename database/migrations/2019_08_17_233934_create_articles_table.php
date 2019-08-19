<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('title')->comment('Title');
            $table->text('article_content')->comment(' Article content');

            $table->integer('user_id')->unsigned()->comment('Foreign Key for users');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            //TODO: category
//            $table->integer('category_id')->unsigned()->comment('Foreign Key');
//            $table->foreign('category_id')
//                ->references('id')
//                ->on('categories')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');

            //TODO: More Time needed..
//            $table->string('slug')->unique()->index()->comment('Slug');
//            $table->string('summary')->comment('Summary');
//            $table->text('origin')->comment('Origin');
//            $table->integer('comment_count')->unsigned()->comment('total comments');
//            $table->integer('view_count')->unsigned()->comment('total page views');
//            $table->integer('favorite_count')->unsigned()->comment('up votes');
//            $table->boolean('published')->comment('Is published?');

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

        Schema::table('articles', function(Blueprint $table){
            $table->dropForeign('articles_user_id_foreign');
        });

//        Schema::table('articles', function(Blueprint $table){
//            $table->dropForeign('articles_category_id_foreign');
//        });
        Schema::drop('articles');
    }
}
