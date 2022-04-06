<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    /** @test */
    public function user_can_create_if_data_is_valid()
    {
        $dataCreate = [
            'name' => $this->faker->name,
            'title' => $this->faker->text,
        ];
        $respone = $this->json('POST', route('post.store', $dataCreate));

        $respone->assertStatus(Response::HTTP_OK);
        $respone->assertJson(fn (AssertableJson $json)=>
            $json->has('data', fn (AssertableJson $json) =>
                $json->where('name', $dataCreate['name'])
                ->etc()
            )->etc()
        );

        $this->assertDatabaseHas('posts', [
           'name' =>  $dataCreate['name'],
           'title' =>  $dataCreate['title'],
        ]);
    }

    /** @test */
    public function user_can_not_create_if_name_is_null()
    {
        $dataCreate = [
            'name' => '',
            'title' => $this->faker->text,
        ];

        $respone = $this->postJson(route('post.store'), $dataCreate);
        $respone->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $respone->assertJson(fn (AssertableJson $json) =>
            $json->has('error', fn (AssertableJson $json) =>
                $json->has('name')
                ->etc()
            )->etc()
        );

    }

    /** @test */
    public function user_can_not_create_if_title_is_null()
    {
        $dataCreate = [
            'name' => $this->faker->name,
            'title' => '',
        ];

        $respone = $this->postJson(route('post.store'), $dataCreate);
        $respone->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $respone->assertJson(fn (AssertableJson $json) =>
        $json->has('error', fn (AssertableJson $json) =>
        $json->has('title')
            ->etc()
        )->etc()
        );

    }


    /** @test */
    public function user_can_not_create_if_data_invalid()
    {
        $dataCreate = [
            'name' => '',
            'title' => '',
        ];
        $respone = $this->postJson(route('post.store'), $dataCreate);

        $respone->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $respone->assertJson(fn (AssertableJson $json) =>
        $json->has('error', fn (AssertableJson $json) =>
        $json->has('title')
            ->has('name')
        )->etc()
        );
    }
    /** @test */
    public function user_can_not_create_if_name_invalid()
    {
        $dataCreate = [
            'name' => 'asc',
            'title' => $this->faker->text,
        ];
        $respone = $this->postJson(route('post.store'), $dataCreate);

        $respone->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $respone->assertJson(fn (AssertableJson $json) =>
        $json->has('error', fn (AssertableJson $json) =>
        $json->has('name')
        )->etc()
        );
    }
}
