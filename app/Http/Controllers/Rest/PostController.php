<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Add this line to import the Validator class


class PostController extends Controller
{
    //
    public function createPost(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'status' => 'required',
            'category_id' => 'required|array',
            // Assuming you have a category_id field in your form
        ], [
            'title.required' => 'title không được bỏ trống',
            'body.required' => 'body không được bỏ trống',
            'status.required' => 'status không được bỏ trống',
            'category_id.required' => 'category_id không được bỏ trống',
            'category_id.array' => 'category_id phải là một mảng',
        ]);

        $post = Post::create($validatedData);

        // Create a new record in the post_category table.
        $postCategories = [];
        foreach ($request->input('category_id') as $categoryId) {
            $postCategories[] = [
                'post_id' => $post->id,
                'category_id' => $categoryId,
            ];
        }

        // Insert all the post_categories in a batch
        PostCategory::insert($postCategories);

        return response()->json(['message' => 'Post created successfully']);
    }

    public function updatePost(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'status' => 'required',
            'category_id' => 'required|array',
            // Assuming you have a category_id field in your form
        ], [
            'title.required' => 'title không được bỏ trống',
            'body.required' => 'body không được bỏ trống',
            'status.required' => 'status không được bỏ trống',
            'category_id.required' => 'category_id không được bỏ trống',
            'category_id.array' => 'category_id phải là một mảng',
        ]);

        $post = Post::find($id); // Find the post by its ID

        // Update the post with the validated data
        $post->update($validatedData);

        // Update the associated category in the post_category table.
        $postCategories = [];
        foreach ($request->input('category_id') as $categoryId) {
            $postCategories[] = [
                'post_id' => $post->id,
                'category_id' => $categoryId,
            ];
        }

        // Remove existing categories for this post and insert the new ones in a batch
        PostCategory::where('post_id', $post->id)->delete();
        PostCategory::insert($postCategories);
        return response()->json(['message' => 'Post updated successfully']);
    }


    public function deletePost($id)
    {

        try {
            //code...
            $post = Post::find($id); // Find the post by its ID
            if ($post) {
                # code...
                Post::deleted($id);
                PostCategory::where('post_id', $id)->delete();
                return response()->json(['message' => 'Post deleted successfully']);
            } else {
                return response()->json(['message' => 'Can not find Post'], 500);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // ...

    public function getAllPosts(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'perPage' => 'required',
        ], [
            'perPage.required' => 'Số phần tử trên trang không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        $perPage = $request->input('perPage', 10); // Default to 10 posts per page, you can adjust this value

        try {
            $posts = Post::with(['categories', 'tags'])->paginate($perPage);

            return response()->json(['posts' => $posts], 200);
        } catch (\Throwable $th) {
            // Handle the exception as needed, e.g., log it or return an error response.
            return response()->json(['message' => 'Error fetching posts'], 500);
        }
    }


    public function detailPost($id)
    {
        try {
            //code...
            $posts = Post::with(['categories', 'tags'])->find($id);

            return response()->json(['posts' => $posts], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Error fetching posts'], 500);
        }
    }
}
