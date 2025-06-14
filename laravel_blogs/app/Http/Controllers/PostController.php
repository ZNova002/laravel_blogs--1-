<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Subscriber;
use App\Mail\NewPostNotification;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $posts = Post::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tags', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('posts.index', compact('posts', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }
    // Phương thức store
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $data = $request->only(['title', 'content', 'category_id']);

        // Xử lý thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = uniqid() . '_' . $thumbnail->getClientOriginalName();
            $thumbnailPath = 'storage/posts/thumbnails';
            $thumbnail->move(public_path($thumbnailPath), $thumbnailName);
            $data['thumbnail'] = $thumbnailPath . '/' . $thumbnailName;
        }

        // Xử lý gallery
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = uniqid() . '_' . $image->getClientOriginalName();
                $imagePath = 'storage/posts/images';
                $image->move(public_path($imagePath), $imageName);
                $imagePaths[] = $imagePath . '/' . $imageName;
            }
            $data['images'] = json_encode($imagePaths);
        }

        // Tạo bài viết
        $post = Post::create($data);

        // Gắn tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        // Gửi email thông báo đến subscribers
        try {
            $subscribers = Subscriber::all();
            foreach ($subscribers as $subscriber) {
                $email = $subscriber->email;
                $mailData = [
                    'post' => [
                        'id' => $post->id,
                        'title' => $post->title,
                        'content' => $post->content,
                        'created_at' => $post->created_at,
                    ],
                    'email' => $email,
                ];
                Mail::to($email)->send(new NewPostNotification($mailData));
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi gửi email thông báo: ' . $e->getMessage());
        }

        return redirect()->route('posts.index')->with('success', 'Tạo bài viết thành công và đã gửi thông báo.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        if (is_string($post->images)) {
            $post->images = json_decode($post->images, true);
        }

        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $data = $request->only(['title', 'content', 'category_id']);

        // Cập nhật thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
                unlink(public_path($post->thumbnail));
            }
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = uniqid() . '_' . $thumbnail->getClientOriginalName();
            $thumbnailPath = 'storage/posts/thumbnails';
            $thumbnail->move(public_path($thumbnailPath), $thumbnailName);
            $data['thumbnail'] = $thumbnailPath . '/' . $thumbnailName;
        }

        // Cập nhật images
        if ($request->hasFile('images')) {
            $oldImages = is_string($post->images) ? json_decode($post->images, true) : [];
            foreach ($oldImages as $oldImage) {
                if (file_exists(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = uniqid() . '_' . $image->getClientOriginalName();
                $imagePath = 'storage/posts/images';
                $image->move(public_path($imagePath), $imageName);
                $imagePaths[] = $imagePath . '/' . $imageName;
            }
            $data['images'] = json_encode($imagePaths);
        }

        $post->update($data);

        // Cập nhật tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('posts.index')->with('success', 'Cập nhật bài viết thành công.');
    }

    public function destroy(Post $post)
    {
        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            unlink(public_path($post->thumbnail));
        }

        if ($post->images) {
            $images = is_string($post->images) ? json_decode($post->images, true) : [];
            foreach ($images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        $post->tags()->detach();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Xóa bài viết thành công.');
    }

    public function show(Post $post)
    {
        if (is_string($post->images)) {
            $post->images = json_decode($post->images, true);
        }

        return view('posts.show', compact('post'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $filePath = 'storage/post_images';
            $file->move(public_path($filePath), $fileName);
            return response()->json(['url' => asset($filePath . '/' . $fileName)]);
        }

        return response()->json(['error' => 'Không tìm thấy file ảnh'], 400);
    }
}
