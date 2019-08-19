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
                'user_id' => $user->_id,
            ],
            5
        );
        //TODO: to be done later... Fatigue is on...
//        $returnedArticles = ?;

        //Action
        $response = $this->json('GET', "/api/users/{$user->_id}/articles")->dump()
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
