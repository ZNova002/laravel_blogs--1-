<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function index()
    {
        $posts = Post::select('id', 'title', 'thumbnail')->latest()->paginate(10);
        return view('comments.index', compact('posts'));
    }

    public function showComments(Post $post)
    {
        $comments = $post->comments()->latest()->get();
        return view('comments.manage', compact('post', 'comments'));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            '_' => 'required_without_all:email,phone|in:valid',
        ], [
            '_.required_without_all' => 'Vui lòng điền ít nhất Email hoặc Số điện thoại.',
        ]);

        Comment::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'post_id' => $post->id,
        ]);

        return redirect()->route('comments.show', $post->id)->with('success', 'Bình luận đã được thêm.');
    }

    public function edit(Comment $comment)
    {
        $post = $comment->post;
        return view('comments.edit', compact('comment', 'post'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            '_' => 'required_without_all:email,phone|in:valid',
        ], [
            '_.required_without_all' => 'Vui lòng điền ít nhất Email hoặc Số điện thoại.',
        ]);

        $comment->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        return redirect()->route('comments.show', $comment->post_id)->with('success', 'Bình luận đã được cập nhật.');
    }

    public function destroy(Comment $comment)
    {
        $post_id = $comment->post_id;
        $comment->delete();

        return redirect()->route('comments.show', $post_id)->with('success', 'Bình luận đã được xóa.');
    }
}
