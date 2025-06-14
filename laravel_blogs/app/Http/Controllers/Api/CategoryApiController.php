<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryApiController extends Controller
{
    /**
     * Lấy danh sách tất cả danh mục.
     * Lấy toàn bộ danh mục từ DB.
     * Chuyển đường dẫn ảnh thành URL truy cập được.
     */
    public function index()
    {
        $categories = Category::all()->map(function ($category) {
            $category->image = $category->image ? asset($category->image) : null;
            return $category;
        });
        return response()->json($categories);
    }

    /**
     * Tạo mới một danh mục.
     * Validate đầu vào: tên, mô tả, ảnh.
     * Nếu có ảnh, lưu vào thư mục `storage/categories`.
     * Tạo danh mục và trả về dữ liệu đã tạo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('storage/categories');

            if (!File::exists($imagePath)) {
                File::makeDirectory($imagePath, 0755, true);
            }

            $image->move($imagePath, $imageName);
            $data['image'] = 'storage/categories/' . $imageName;
        }

        $category = Category::create($data);
        $category->image = $category->image ? asset($category->image) : null;

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Lấy chi tiết một danh mục cụ thể.
     * Tìm danh mục theo ID.
     * Nếu không tìm thấy, trả về lỗi 404.
     * Nếu có, trả về thông tin danh mục.
     */
    public function show($category)
    {
        $category = Category::find($category);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }
        $category->image = $category->image ? asset($category->image) : null;
        return response()->json([
            'success' => true,
            'data' => $category,
        ], 200);
    }

    /**
     * Cập nhật một danh mục.
     * Tìm danh mục theo ID.
     * Validate dữ liệu và xử lý ảnh mới nếu có.
     * Cập nhật thông tin danh mục và trả về kết quả.
     */
    public function update(Request $request, $category)
    {
        $category = Category::find($category);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('storage/categories');

            if (!File::exists($imagePath)) {
                File::makeDirectory($imagePath, 0755, true);
            }

            $image->move($imagePath, $imageName);
            $data['image'] = 'storage/categories/' . $imageName;
        }

        $category->update($data);
        $category->image = $category->image ? asset($category->image) : null;

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ], 200);
    }

    /**
     * Xoá một danh mục.
     * Tìm danh mục theo ID.
     * Nếu có ảnh thì xoá ảnh khỏi ổ đĩa.
     * Xoá danh mục khỏi cơ sở dữ liệu.
     */
    public function destroy($category)
    {
        $category = Category::find($category);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
