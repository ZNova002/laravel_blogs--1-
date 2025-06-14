<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;

class ProfileApiController extends Controller
{
    // Lấy thông tin người dùng đang đăng nhập
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user,
        ], 200);
    }

    // Cập nhật thông tin cá nhân người dùng
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $avatar = $request->file('avatar');
            $avatarName = uniqid() . '_' . $avatar->getClientOriginalName();
            $avatarPath = 'storage/avatars';

            if (!File::exists(public_path($avatarPath))) {
                File::makeDirectory(public_path($avatarPath), 0755, true);
            }

            $avatar->move(public_path($avatarPath), $avatarName);
            $data['avatar'] = $avatarPath . '/' . $avatarName;
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ], 200);
    }
}
