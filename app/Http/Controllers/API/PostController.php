<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
    $posts = Post::orderByDesc('created_at')->paginate(10);
    $postResources = PostResource::collection($posts);

    return response()->json([
        'data' => $postResources,
        'status' => 'success'
    ], 200);
}


/**
 * Store a newly created resource in storage.
 */
public function store(PostStoreRequest $request)
{
    $validate_data = $request->validated();
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = $image->getClientOriginalName();
        $image->storeAs('public/images', $imageName);
        $validate_data['image'] = $imageName;
    }
    $post = Post::create($validate_data);
    $postResource = new PostResource($post);
    return response()->json([
        'data' => $postResource,
        'status' => 'success'
    ],201);
}

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $postResource = new PostResource($post);
        return response()->json([
            'data' => $postResource,
            'status' => 'success'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $validate_data = $request->validated();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $validate_data['image'] = $imageName;
            if($post->image !== null && file_exists(public_path('images/'.$post->image))){
                unlink(public_path('images/'.$post->image));
            }
        }
        $post->update($validate_data);
        $postResource = new PostResource($post);
        return response()->json([
            'data' => $postResource,
            'status' => 'success'
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post,Request $request)
    {
        if($request->user()->id !== $post->user_id){
            return response()->json([
                'message' => 'You are not authorized to delete this post'
            ], 401);
        }
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully'
        ],201);
    }
}
