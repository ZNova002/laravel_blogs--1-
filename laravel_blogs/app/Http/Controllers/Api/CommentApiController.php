<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class CommentApiController extends Controller
{
     /**
     * Lấy danh sách bài viết (dành để hiển thị bình luận).
     * Truy vấn các bài viết mới nhất.
     * Chỉ chọn cột id, title, thumbnail.
     * Trả về dạng phân trang (12 bài/trang).
     */
    public function index()
    {
        $posts = Post::select('id', 'title', 'thumbnail')->latest()->paginate(12);
        return response()->json([
            'status' => 'success',
            'data' => $posts,
        ], 200);
    }

     /**
     * Lấy danh sách bình luận của một bài viết cụ thể.
     * Truy vấn tất cả bình luận liên quan đến bài viết.
     * Trả về thông tin bài viết và danh sách bình luận.
     */
    public function comments(Post $post)
    {
        $comments = $post->comments()->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                'post' => $post->only('id', 'title'),
                'comments' => $comments,
            ],
        ], 200);
    }

    /**
     * Thêm bình luận mới cho bài viết.
     * Không chỉnh sửa đoạn này theo yêu cầu.
     */
    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            '_' => 'required_without_all:email,phone|in:valid',
        ], [
            '_.required_without_all' => 'At least one of email or phone is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comment = Comment::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'post_id' => $post->id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $comment,
            'message' => 'Comment created successfully.',
        ], 201);
    }

   /**
     * Cập nhật nội dung của một bình luận.
     * Kiểm tra bình luận có thuộc về bài viết không.
     * Validate lại các trường dữ liệu.
     * Cập nhật nội dung bình luận.
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment does not belong to this post.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            '_' => 'required_without_all:email,phone|in:valid',
        ], [
            '_.required_without_all' => 'At least one of email or phone is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comment->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $comment,
            'message' => 'Comment updated successfully.',
        ], 200);
    }

     /**
     * Xóa một bình luận khỏi bài viết.
     * Kiểm tra comment có thuộc post không.
     * Nếu đúng thì xóa bình luận.
     */
    public function destroy(Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment does not belong to this post.',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully.',
        ], 200);
    }
}
