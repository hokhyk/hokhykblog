<?php

namespace Tests\Feature;

use App\Entities\Blog\Article;
use App\Entities\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserArticleRelationTest extends TestCase
{

    use withFaker;

    /**
     * @group testCanViewArticlesWithAuthorInfo
     */
    public function testCanViewArticlesWithAuthorInfo() {

        //Arrangement
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(
            [
                '_id' => $this->faker->uuid,
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'phone' => $this->faker->unique()->phoneNumber,
                'password' => $this->faker->password(),
            ]
        );

        $articles = factory(Article::class)->create(
            [
                '_id' => $this->faker->uuid,
                'title' => $this->faker->title,
                'article_content' => $this->faker->paragraph(1),
//                'user_id' => $user->_id,
                'user_id' => '1b816700-27d2-3ecc-99e6-488eafc4c3ea',
            ]
        );
        //TODO: to be done later... Fatigue is on...
//        $returnedArticles = ?;

        //Action
//        $response = $this->json('GET', "/api/users/{$user->_id}/articles")->dump()
        $response = $this->json('GET', "/api/users/1b816700-27d2-3ecc-99e6-488eafc4c3ea/articles")->dump()
            ->assertStatus(200)
//            ->assertJsonFragment(json_decode(json_encode($returnedArticles), true))
            ->assertJsonStructure(
                ['code', 'message', 'result' => [
                        ['_id', 'title', 'article_content', 'author', 'created_at', 'updated_at',
                            'author_info' => [
                                '_id',
                                'name',
                                'email'
                            ]
                        ]
                    ]
                ]
            );
    }

}
