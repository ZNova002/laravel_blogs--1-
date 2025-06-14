<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchApiController extends Controller
{
    public function search(Request $request)
    {
        // Kiểm tra hợp lệ dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
                'data' => []
            ], 400);
        }

        $queryString = $request->query('q', '');
        $category = $request->query('category');
        $tags = $request->query('tags');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $perPage = $request->query('per_page');

        // ấy giá trị filter từ request
        $query = Post::query()
            ->select('id', 'title', 'content', 'category_id', 'thumbnail', 'images', 'created_at')
            ->with([
                'category' => fn($q) => $q->select('id', 'name'),
                'tags' => fn($q) => $q->select('tags.id', 'tags.name')
            ]);

        // Điều kiện tìm kiếm theo từ khóa
        if ($queryString) {
            $query->where(function ($q) use ($queryString) {
                $q->where('title', 'LIKE', "%{$queryString}%")
                  ->orWhere('content', 'LIKE', "%{$queryString}%")
                  ->orWhereHas('tags', fn($q) => $q->where('name', 'LIKE', "%{$queryString}%"))
                  ->orWhereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$queryString}%"));
            });
        }

        // Phân trang và chuẩn hóa đường dẫn ảnh
        $query->when($category, fn($q) => $q->whereHas('category', fn($q) => $q->where('name', $category)))
              ->when($tags, function ($q) use ($tags) {
                  $tagsArray = array_map('trim', explode(',', $tags));
                  $q->whereHas('tags', fn($q) => $q->whereIn('name', $tagsArray));
              })
              ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
              ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate));

        // Trả kết quả JSON
        $posts = $query->latest('created_at')
                       ->paginate($perPage)
                       ->through(function ($post) {
                           $post->thumbnail = $post->thumbnail ? asset($post->thumbnail) : null;
                           $post->images = $post->images ? array_map(fn($image) => asset($image), is_string($post->images) ? json_decode($post->images, true) : $post->images) : [];
                           return $post;
                       });

        return response()->json([
            'message' => $posts->isEmpty() ? 'Không tìm thấy bài viết phù hợp.' : 'Kết quả tìm kiếm và lọc thành công.',
            'data' => $posts
        ], 200);
    }
}
