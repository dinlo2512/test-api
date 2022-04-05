<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    /** @test */
    public function user_can_update_if_data_valid()
    {
        $post = Post::factory()->create();
        $dataUpdate = [
          'name' => $this->faker->name,
            'title' => $this->faker->text,
        ];

        $response = $this->putJson(route('post.update', $post->id), $dataUpdate);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(fn (AssertableJson  $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json->where('name',$dataUpdate['name'])
                ->etc()
            )
            ->etc()
        );

        $this->assertDatabaseHas('posts', [
           'name' => $dataUpdate['name'],
           'title' => $dataUpdate['title'],
        ]);
    }

    /** @test */
    public function user_can_not_update_if_name_is_null()
    {
        $post = Post::factory()->create();
        $dataUpdate = [
            'name' => '',
            'title' => $this->faker->text,
        ];

        $response = $this->putJson(route('post.update', $post->id), $dataUpdate);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(fn (AssertableJson  $json) =>
            $json->has('error', fn (AssertableJson  $json) =>
                $json->has('name')
                ->etc()
            )->etc()
        );
    }

    /** @test */
    public function user_can_not_update_if_title_is_null()
    {
        $post = Post::factory()->create();
        $dataUpdate = [
            'name' => $this->faker->name,
            'title' => '',
        ];

        $response = $this->putJson(route('post.update', $post->id), $dataUpdate);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(fn (AssertableJson  $json) =>
        $json->has('error', fn (AssertableJson  $json) =>
        $json->has('title')
            ->etc()
        )->etc()
        );
    }

    /** @test */
    public function user_can_not_update_if_id_not_exists()
    {
        $postId  = -10000;
        $dataUpdate = [
            'name' => $this->faker->name,
            'title' => $this->faker->text,
        ];

        $response = $this->putJson(route('post.update', $postId), $dataUpdate);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(fn (AssertableJson  $json) =>
        $json->has('message')
        );
    }
}
