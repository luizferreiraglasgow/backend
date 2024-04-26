<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }
    public function showPosts($id)
    {
        $category = Category::findOrFail($id);
        $posts = $category->posts()->with('user:id,name')->get(); // Include the user relationship

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Posts retrieved successfully for category ' . $category->name,
            'data' => $posts,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories|max:255',
        ], [
            'name.required' => 'The category is required.',
            'name.unique' => 'The category already exists.',
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

        // Create a new category
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $category->id . '|max:255',
        ], [
            'name.unique' => 'The category already exists.',
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

        $category->name = $request->name;
        $category->save();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }


    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Category not found',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Category deleted successfully',
        ]);
    }
}
