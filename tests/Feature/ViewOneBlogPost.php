<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewOneBlogPost extends TestCase
{
    public function testCanViewOneBlogPost():void {
        //Arrangement
        //Create a blog post
        $article = [
            'title' => 'my first post title',
            'content' => 'my first post content',
        ];

        $post = Post::create($article);

        //Action
        //Visit a route to the post
        $resp = $this->get("/post/{$post->id}");

        //Assertions
        //Assert response has a 200 status code
        $resp->assertStatus(200);

        //Assert response has a data payload with exact post.
        $resp->assertJson($article);

        //Assert response has a data payload with published time
        $resp->assert

    }
}
