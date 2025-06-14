<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthApiController extends Controller
{
    /**
     * Xử lý đăng nhập người dùng.
     * Xác thực thông tin đăng nhập (email và password).
     * Nếu đúng, tạo token truy cập cho người dùng.
     * Trả về token và thông tin người dùng.
     * Nếu sai, trả về lỗi 401.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['error' => 'Email hoặc mật khẩu không đúng'], 401);
    }

    /**
     * Đăng xuất người dùng (xóa token hiện tại).
     * Xoá token truy cập hiện tại của người dùng.
     * Trả về thông báo "Logged out".
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    /**
     * Lấy thông tin người dùng đang đăng nhập.
     * Trả về thông tin của người dùng đã xác thực qua token.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

