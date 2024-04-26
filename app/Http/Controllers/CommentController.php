<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user:id,name')->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Comments retrieved successfully',
            'data' => $comments,
        ]);
    }

    public function show($id)
    {
        $comment = Comment::with('user:id,name')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Comment retrieved successfully',
            'data' => $comment,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:255',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The specified user does not exist.',
            'post_id.required' => 'The post ID field is required.',
            'post_id.exists' => 'The specified post does not exist.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 255 characters.',
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

        // Create a new comment
        $comment = new Comment();
        $comment->user_id = $request->user_id;
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Comment created successfully',
            'data' => $comment,
        ], 201);
    }

    public function commentsForPost($postId)
    {
        $comments = Comment::with('user:id,name')->where('post_id', $postId)->get();

        if ($comments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'No comments found for the specified post',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Comments retrieved successfully',
            'data' => $comments,
        ]);
    }
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $request->validate([
            'content' => 'required|string|max:255',
        ], [
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 255 characters.',
        ]);

        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Comment not found',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
