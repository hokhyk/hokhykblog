<?php

namespace Tests\Feature;

use App\Entities\Blog\Article;
use App\Entities\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleUserRelationTest extends TestCase
{
    use withFaker;

    /**
     * @group ViewArticleWithUserID
     */
    public function testCanViewArticleWithUserID() {

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

        $article = factory(Article::class)->create(
            [
                '_id' => $this->faker->uuid,
                'title' => $this->faker->title,
                'article_content' => $this->faker->paragraph(1),
                'user_id' => $user->_id,
            ]
        );

        //Action
        $response = $this->json('GET', "/api/users/{$user->_id}/articles/{$article->_id}")
            ->assertStatus(200)
            ->assertJsonFragment(json_decode(json_encode($article), true))
            ->assertJsonStructure(
                ['code', 'message', 'result' =>
                    ['_id', 'title', 'article_content', 'author', 'created_at', 'updated_at']
                ]
            );
    }
}
