<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;  //problematic under jenssegers/laravel-mongodb driver.
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Entities\Blog\Article;

/**
 * Class ViewOneArticleTest
 * @package Tests\Feature
 */
class ArticleTest extends TestCase
{
//    use RefreshDatabase;  //A workaround for working with mongodb :
//           https://github.com/jenssegers/laravel-mongodb/issues/1475
//           https://stackoverflow.com/questions/49320912/laravel-5-6-unit-test-call-to-a-member-function-begintransaction-on-null

//    use DatabaseMigrations;
    use withFaker;

    /**
     * @group destroyArticle
     */
    public function testCanDestroyArticle() {
        //Arrangement
        $this->withoutExceptionHandling();

        $article = factory(Article::class)->create();

        //Action
        $response = $this->json('DELETE', "/api/articles/{$article->_id}")
            ->assertJson(['code' => 200, 'message' => 'Article is deleted.']);

        //assert database record is deleted.
        $this->assertDatabaseMissing('articles', json_decode(json_encode($article), true));
    }

    /**
     * @group canNotDestroyEmpty
     */
    public function testCanNotDestroyEmptyArticle() {
        //Arrangement
        $this->withoutExceptionHandling();

        $id = $this->faker->randomDigitNotNull();

        //Action
        $response = $this->json('DELETE', "/api/articles/{$id}")
            ->assertJson(['code' => 417, 'message' => 'Not an article found.']);
    }

    /**
     * @group updateArticle
     */
    public function testCanUpdateArticle() {
        //Arrangement
        $this->withoutExceptionHandling();
        // $data to be submitted for update.
        $data = [
            'title' => $this->faker->title,
            'article_content' => $this->faker->paragraph(2)
        ];

        $article = factory(Article::class)->create();

        //Action
        $response = $this->json('PUT', "/api/articles/{$article->_id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure(
                ['code', 'message', 'result' => ['_id', 'title', 'article_content', 'created_at', 'updated_at']]
            )
            ->assertJsonFragment($data);

        //assert database record is updated
        $this->assertDatabaseHas('articles',
            $data);
    }


    /**
     * @group viewAllArticles
     */
    public function testCanViewAllArticles() {
        //Arrangement
        $this->withoutExceptionHandling();

        $article1 = factory(Article::class)->create();
        $article2 = factory(Article::class)->create();

        //Action
        $response = $this->json('GET', "/api/articles")
            ->assertStatus(200)
            ->assertJson(
                [
                    '0' => json_decode(json_encode($article1), true),
                    '1' => json_decode(json_encode($article2), true)
                ]
            );
    }


    /**
     * @group storeArticle
     */
    public function testCanStoreOneArticle() {
        //Arrangement
        $this->withoutExceptionHandling();

        //Create a blog Article
        $article = [
            'title' => $this->faker->title,
            'article_content' => $this->faker->paragraph(3)
        ];

        //Action
        //Visit a route to create the article
//        $response = $this->postJson("/api/articles", $article)
        $response = $this->json('POST', "/api/articles", $article)
            ->assertStatus(201)
            ->assertJson(json_decode(json_encode($article), true))
            ->assertJsonStructure(
                ['_id', 'title', 'article_content', 'created_at', 'updated_at']
            );

        //assert database records
        $this->assertDatabaseHas('articles',
            [
                'title' => $article['title'],
                'article_content' => $article['article_content']
            ]
        );
    }


    /**
     * @group viewArticle
     */
    public function testCanViewOneArticle() {
        //Arrangement
        $this->withoutExceptionHandling();
        //Create a blog Article
        $article = factory(Article::class)->create();

        //Action
        //Visit a route to the article   https://stackoverflow.com/questions/42657551/match-jsonstructure-in-phpunit-test-laravel-5-4
        $response = $this->json('GET', "/api/articles/{$article->_id}")
            ->assertStatus(200)
            ->assertJsonFragment(json_decode(json_encode($article), true))
            ->assertJsonStructure(
                ['code', 'message', 'result' => ['_id', 'title', 'article_content', 'created_at', 'updated_at']]
            );
    }
}
