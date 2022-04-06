<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetPostTest extends TestCase
{
    /** @test */

    public function user_can_get_post_if_exists()
    {
        $post = Post::factory()->create();
        $response = $this->getJson(route('post.show', $post->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json->where('name', $post->name)
                ->etc()
            )
        );
    }

    /** @test */
    public function user_can_not_get_post_if_not_exists()
    {
        $postID = -1;
        $response = $this->getJson(route('post.show',$postID));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
