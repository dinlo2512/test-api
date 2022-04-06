<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    /** @test */
    public function user_can_delete_if_id_valid()
    {
        $post = Post::factory()->create();
        $countBefore = Post::count();
        $response = $this->deleteJson(route('post.destroy', $post->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('message')
            ->etc()
        );
        $countAfter = Post::count();
        $this->assertEquals($countBefore - 1, $countAfter);
    }

    /** @test */
    public function user_can_delete_if_id_invalid()
    {
        $postId = -1;
        $response = $this->deleteJson(route('post.destroy', $postId));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(fn(AssertableJson $json) => $json->has('message')
            ->etc()
        );
    }
}
