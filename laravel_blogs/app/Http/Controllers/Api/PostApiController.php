<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Subscriber;
use App\Mail\NewPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;

class PostApiController extends Controller
{
    // Lấy danh sách bài viết (có phân trang và kèm ảnh đầy đủ URL)
    public function index()
    {
        $posts = Post::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])->latest()->paginate(12)->through(function ($post) {
            $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
            $post->images = $post->images ? array_map(function ($image) {
                return asset($image);
            }, is_string($post->images) ? json_decode($post->images, true) : $post->images) : [];
            return $post;
        });

        return response()->json($posts);
    }

    // Xem chi tiết bài viết
    public function show(Post $post)
    {
        $post->load(['category' => function ($query) {
            $query->select('id', 'name');
        }]);
        $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
        $post->images = $post->images ? array_map(function ($image) {
            return asset($image);
        }, is_string($post->images) ? json_decode($post->images, true) : $post->images) : [];

        return response()->json($post);
    }

    // Tạo mới bài viết và gửi email đến subscribers
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'category_id' => 'required|exists:categories,id',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'content_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'tags' => 'nullable|array',
            ]);

            $data = $request->only(['title', 'content', 'category_id']);

            // Xử lý thumbnail
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = uniqid() . '_' . $thumbnail->getClientOriginalName();
                $thumbnailPath = 'storage/posts/thumbnails';
                if (!file_exists(public_path($thumbnailPath))) {
                    mkdir(public_path($thumbnailPath), 0755, true);
                }
                $thumbnail->move(public_path($thumbnailPath), $thumbnailName);
                $data['thumbnail'] = $thumbnailPath . '/' . $thumbnailName;
            }

            // Xử lý images
            if ($request->hasFile('images')) {
                $imagePaths = [];
                $imagePath = 'storage/posts/images';
                if (!file_exists(public_path($imagePath))) {
                    mkdir(public_path($imagePath), 0755, true);
                }
                foreach ($request->file('images') as $image) {
                    $imageName = uniqid() . '_' . $image->getClientOriginalName();
                    $image->move(public_path($imagePath), $imageName);
                    $imagePaths[] = $imagePath . '/' . $imageName;
                }
                $data['images'] = json_encode($imagePaths);
            }

            // Xử lý content_images
            if ($request->hasFile('content_images')) {
                $contentImages = [];
                $content = $data['content'];
                $imagePath = 'storage/post_images';
                if (!file_exists(public_path($imagePath))) {
                    mkdir(public_path($imagePath), 0755, true);
                }
                foreach ($request->file('content_images') as $image) {
                    $imageName = uniqid() . '_' . $image->getClientOriginalName();
                    $image->move(public_path($imagePath), $imageName);
                    $contentImages[] = asset($imagePath . '/' . $imageName);
                }
                // Kiểm tra số lượng blob URLs
                $blobCount = preg_match_all('/<img[^>]+src=["\']blob:[^"\']+["\']/i', $content);
                if (count($contentImages) < $blobCount) {
                    throw new \Exception('Số lượng ảnh trong nội dung không khớp với content_images');
                }
                // Thay blob URL bằng URL thật
                $index = 0;
                $content = preg_replace_callback(
                    '/<img[^>]+src=["\']blob:[^"\']+["\'][^>]*>/i',
                    function ($match) use ($contentImages, &$index) {
                        if (isset($contentImages[$index])) {
                            $newSrc = $contentImages[$index];
                            $index++;
                            return str_replace(
                                preg_match('/src=["\'][^"\']+["\']/i', $match[0], $srcMatch) ? $srcMatch[0] : '',
                                "src=\"$newSrc\"",
                                $match[0]
                            );
                        }
                        return $match[0];
                    },
                    $content
                );
                $data['content'] = $content;
            }

            $post = Post::create($data);

            // Xử lý tags
            if ($request->has('tags')) {
                $tagIds = [];
                foreach ($request->tags as $tagName) {
                    $tag = \App\Models\Tag::firstOrCreate(
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
                \Log::error('Lỗi gửi email thông báo qua API: ' . $e->getMessage());
            }

            $post->load(['category' => function ($query) {
                $query->select('id', 'name');
            }]);
            $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
            $post->images = $post->images ? array_map(function ($image) {
                return asset($image);
            }, json_decode($post->images, true) ?: []) : [];

            return response()->json([
                'success' => true,
                'message' => 'Tạo bài viết thành công và đã gửi thông báo đến subscribers',
                'post' => $post
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server khi tạo bài viết: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required',
            'category_id' => 'sometimes|required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'tags' => 'nullable|array',
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
                $tag = \App\Models\Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        $post->load(['category' => function ($query) {
            $query->select('id', 'name');
        }]);
        $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
        $post->images = $post->images ? array_map(function ($image) {
            return asset($image);
        }, json_decode($post->images, true) ?: []) : [];

        return response()->json([
            'message' => 'Cập nhật bài viết thành công',
            'post' => $post
        ]);
    }

    // Xoá bài viết (kèm ảnh và xoá liên kết tag)
    public function destroy(Post $post)
    {
        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            unlink(public_path($post->thumbnail));
        }
        if ($post->images) {
            $images = is_string($post->images) ? json_decode($post->images, true) : $post->images;
            foreach ($images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }
        $post->tags()->detach();
        $post->delete();

        return response()->json(['message' => 'Xóa bài viết thành công']);
    }

    // Lấy bài viết cùng danh mục (trừ chính nó)
    public function related(Post $post)
    {
        $relatedPosts = Post::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($relatedPost) {
                $relatedPost->thumbnail = $relatedPost->thumbnail ? asset($relatedPost->thumbnail) : null;
                $relatedPost->images = $relatedPost->images ? array_map(function ($image) {
                    return asset($image);
                }, is_string($relatedPost->images) ? json_decode($relatedPost->images, true) : $relatedPost->images) : [];
                return $relatedPost;
            });

        return response()->json($relatedPosts);
    }

    // Lấy bài viết theo danh mục
    public function postsByCategory($categoryId)
    {
        $posts = Post::with(['category' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where('category_id', $categoryId)
            ->latest()
            ->get()
            ->map(function ($post) {
                $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
                $post->images = $post->images ? array_map(function ($image) {
                    return asset($image);
                }, is_string($post->images) ? json_decode($post->images, true) : $post->images) : [];
                return $post;
            });

        return response()->json($posts);
    }

    // Lấy danh sách tag của bài viết
    public function tags(Post $post)
    {
        $tags = $post->tags()->get();
        return response()->json($tags);
    }

    // API tải ảnh (dùng trong trình soạn thảo như Tiptap)
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'type' => 'nullable|string|in:thumbnail,content,post_image'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $type = $request->input('type', 'content');
                $folder = match ($type) {
                    'thumbnail' => 'storage/posts/thumbnails',
                    'post_image' => 'storage/posts/images',
                    default => 'storage/post_images',
                };

                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }
                $file->move(public_path($folder), $fileName);
                return response()->json([
                    'success' => true,
                    'url' => asset($folder . '/' . $fileName)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy file ảnh'
            ], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server khi tải ảnh'
            ], 500);
        }
    }

    // Lấy tất cả danh mục
    public function allCategories()
    {
        return response()->json(\App\Models\Category::select('id', 'name')->get());
    }

    // Lấy tất cả tag
    public function allTags()
    {
        return response()->json(\App\Models\Tag::select('id', 'name')->get());
    }

}
