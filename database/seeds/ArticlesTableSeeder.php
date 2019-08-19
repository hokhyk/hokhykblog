<?php

use App\Entities\Blog\Article;
use App\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Article::class, 5)->create();
//        factory(Article::class, 5)->create()->each(
//            function($article){
//                $article->user()->save(factory(User::class)->make());
//                $article->user()->create([factory(User::class)->make()]);
//            }
//        );
//        DB::insert();
    }
}
