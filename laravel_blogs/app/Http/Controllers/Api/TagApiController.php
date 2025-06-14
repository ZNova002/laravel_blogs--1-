<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagApiController extends Controller
{
    // Lấy danh sách tất cả tags.
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    // Lấy thông tin chi tiết 1 tag theo ID.
    public function show($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }
        return response()->json($tag);
    }

    // Tạo mới một tag.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'message' => 'Tag created successfully',
            'tag' => $tag,
        ], 201);
    }

    /**
     * Cập nhật tag theo ID.
     *
     * - Kiểm tra name không trùng với các tag khác.
     * - Nếu không tồn tại tag thì báo lỗi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,
        ]);

        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'message' => 'Tag updated successfully',
            'tag' => $tag,
        ]);
    }

    /*
     * Xóa một tag theo ID.
     *
     * - Nếu tag tồn tại: xóa các quan hệ với post trước, rồi xóa tag.
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        $tag->posts()->detach();
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }

    public function getTagsByPostId($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $tags = $post->tags()->get();
        return response()->json($tags);
    }
}
