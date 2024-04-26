<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user:id,name')->get(); // Eager load the user relationship with only id and name columns
        return response()->json($posts);
    }

    public function show($id)
    {
        $post = Post::with('user:id,name')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Post retrieved successfully',
            'data' => $post,
        ]);
    }
    public function postsForUser($userId)
{
    $user = User::findOrFail($userId);
    $posts = $user->posts()->get(); // Fetch all posts for the user

    if ($posts->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'code' => 404,
            'message' => 'No posts found for the specified user',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'code' => 200,
        'message' => 'Posts retrieved successfully for user ' . $user->name,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
        ],
        'posts' => $posts,
    ]);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'category_id.required' => 'The category ID field is required.',
            'category_id.exists' => 'The selected category ID is invalid.',
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
        ]);

        // If validation fails, return error response with specific validation error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422, // Unprocessable Entity
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all()
            ], 422);
        }

        // Create a new post
        $post = new Post();
        $post->user_id = $request->user_id;
        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The user ID does not exist.',
            'category_id.required' => 'The category ID field is required.',
            'category_id.exists' => 'The category ID does not exist.',
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422, // Unprocessable Entity
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $post->user_id = $request->user_id;
        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Post updated successfully',
            'data' => $post,
        ]);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Post not found',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Post deleted successfully',
        ]);
    }
}
