<?php
namespace App\Http\Controllers\Api;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SubscriberApiController extends Controller
{
    /**
     * Đăng ký email nhận thông báo (newsletter, cập nhật bài viết,...)
     * Kiểm tra tính hợp lệ của email.
     * Kiểm tra email đã tồn tại chưa (unique).
     * Lưu vào bảng `subscribers`.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        Subscriber::create([
            'email' => $validated['email'],
        ]);

        return response()->json([
            'message' => 'Đăng ký nhận thông báo thành công!',
            'success' => true
        ], 201);
    }

     /**
     * Hủy đăng ký nhận thông báo theo email
     * Email được truyền vào dạng base64 để tránh bị index trực tiếp trên URL.
     * Nếu email tồn tại trong CSDL thì xóa.
     */
    public function unsubscribe($email): JsonResponse
    {
        $email = base64_decode($email);
        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            $subscriber->delete();
            return response()->json([
                'message' => 'Bạn đã hủy đăng ký thành công!',
                'success' => true
            ], 200);
        }

        return response()->json([
            'message' => 'Email không tồn tại trong danh sách đăng ký.',
            'success' => false
        ], 404);
    }
}
