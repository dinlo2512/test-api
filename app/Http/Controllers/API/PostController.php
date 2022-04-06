<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrearePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class PostController extends Controller
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->post->paginate(30);

        $postResource = new PostCollection($posts);

        return response()->json([
            'data' => $postResource
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $dataCreate = $request->all();
        $posts = $this->post->create($dataCreate);

        $postResource = new PostResource($posts);

        return response()->json([
            'data' => $postResource
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        $postResource = new PostResource($post);

        return response()->json([
            'data' => $postResource
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CrearePostRequest $request, $id)
    {
        $post = $this->post->findOrFail($id);
        $dataUpdate = $request->all();
        $post->update($dataUpdate);

        $postResource = new PostResource($post);

        return response()->json([
            'data' => $postResource
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $posts = $this->post->findOrFail($id);
        $posts->delete();

        $postResource = new PostResource($posts);

        return $this->returnSuccess($postResource, 'success', Response::HTTP_OK);
    }
}
