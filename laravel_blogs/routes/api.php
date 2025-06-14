<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\TagApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\AboutApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\SubscriberApiController;
use App\Http\Controllers\Api\SiteSettingsApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProfileApiController;

// Nhóm các route xác thực người dùng
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']); // Đăng nhập
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum'); // Đăng xuất
    Route::get('/user', [AuthApiController::class, 'user'])->middleware('auth:sanctum'); // Lấy thông tin người dùng
});

// Nhóm các route yêu cầu đăng nhập
Route::middleware('auth:sanctum')->group(function () {
    // Hồ sơ người dùng
    Route::get('/profile', [ProfileApiController::class, 'show']); // Xem hồ sơ
    Route::post('/profile', [ProfileApiController::class, 'update']); // Cập nhật hồ sơ

    // Danh mục
    Route::get('/categories/{category}', [CategoryApiController::class, 'show']); // Chi tiết danh mục
    Route::post('/categories', [CategoryApiController::class, 'store']); // Thêm danh mục mới
    Route::match(['put', 'post'], '/categories/{category}', [CategoryApiController::class, 'update']); // Cập nhật danh mục
    Route::delete('/categories/{category}', [CategoryApiController::class, 'destroy']); // Xóa danh mục
    Route::get('/categories/all', [CategoryApiController::class, 'allCategories']); // Lấy tất cả danh mục (bao gồm ẩn)

    // Thẻ (tags)
    Route::put('/tags/{id}', [TagApiController::class, 'update']); // Cập nhật thẻ
    Route::delete('/tags/{id}', [TagApiController::class, 'destroy']); // Xóa thẻ
    Route::post('/tags', [TagApiController::class, 'store']); // Thêm thẻ mới

    // Bài viết
    Route::post('/posts', [PostApiController::class, 'store']); // Tạo bài viết mới
    Route::put('/posts/{post}', [PostApiController::class, 'update']); // Cập nhật bài viết
    Route::delete('/posts/{post}', [PostApiController::class, 'destroy']); // Xóa bài viết
    Route::post('/posts/upload-image', [PostApiController::class, 'uploadImage']); // Upload ảnh bài viết (trong nội dung)

    // Bình luận
    Route::delete('/posts/{post}/comments/{comment}', [CommentApiController::class, 'destroy']); // Xóa bình luận
    Route::put('/posts/{post}/comments/{comment}', [CommentApiController::class, 'update']); // Cập nhật bình luận
    Route::patch('/posts/{post}/comments/{comment}', [CommentApiController::class, 'update']); // Cập nhật bình luận (partial)

    // Liên hệ
    Route::get('/contacts', [ContactApiController::class, 'index']); // Danh sách liên hệ
    Route::get('/contacts/{id}', [ContactApiController::class, 'show']); // Chi tiết liên hệ
    Route::delete('/contacts/{id}', [ContactApiController::class, 'destroy']); // Xóa liên hệ
});

// Nhóm các route công khai, giới hạn tốc độ (60 lần/phút)
Route::middleware(['throttle:60,1'])->group(function () {
    // Trang tĩnh
    Route::get('/about', [AboutApiController::class, 'show']); // Lấy nội dung trang giới thiệu
    Route::get('/settings', [SiteSettingsApiController::class, 'getSettings']); // Cài đặt trang

    // Gửi liên hệ và đăng ký nhận tin
    Route::post('/contacts', [ContactApiController::class, 'store']); // Gửi form liên hệ
    Route::post('/subscribe', [SubscriberApiController::class, 'store']); // Đăng ký nhận tin
    Route::get('/unsubscribe/{email}', [SubscriberApiController::class, 'unsubscribe']); // Hủy nhận tin

    // Danh mục
    Route::get('/categories', [CategoryApiController::class, 'index']); // Danh sách danh mục

    // Tìm kiếm
    Route::get('/search', [SearchApiController::class, 'search'])->name('api.search'); // Tìm kiếm nội dung

    // Thẻ
    Route::get('/tags', [TagApiController::class, 'index']); // Danh sách thẻ
    Route::get('/posts/{post}/tags', [PostApiController::class, 'tags'])->name('api.posts.tags'); // Thẻ liên kết với bài viết

    // Bài viết
    Route::get('/posts', [PostApiController::class, 'index']); // Danh sách bài viết
    Route::get('/posts/{post}', [PostApiController::class, 'show']); // Chi tiết bài viết
    Route::get('/posts/{post}/related', [PostApiController::class, 'related'])->name('api.posts.related'); // Bài viết liên quan

    // Bình luận
    Route::get('/comments', [CommentApiController::class, 'index']); // Danh sách bình luận (toàn trang)
    Route::get('/posts/{post}/comments', [CommentApiController::class, 'comments']); // Bình luận của bài viết
    Route::post('/posts/{post}/comments', [CommentApiController::class, 'store']); // Gửi bình luận cho bài viết
});
