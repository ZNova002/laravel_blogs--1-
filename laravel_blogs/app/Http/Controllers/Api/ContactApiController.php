<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
     /**
     * Lưu thông tin liên hệ từ người dùng gửi lên.
     * Kiểm tra và xác thực các trường `name`, `email`, `message`.
     * Lưu vào cơ sở dữ liệu bảng `contacts`.
     * Trả về thông báo và dữ liệu vừa lưu.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($data);

        return response()->json([
            'message' => 'Thông tin liên hệ đã được gửi thành công!',
            'contact' => $contact
        ], 201);
    }

    /**
     * Lấy danh sách tất cả các liên hệ đã gửi.
     * Truy vấn danh sách liên hệ theo thứ tự mới nhất.
     * Trả về danh sách các liên hệ.
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();

        return response()->json([
            'contacts' => $contacts
        ]);
    }

    /**
     * Lấy chi tiết một liên hệ theo ID.
     * Tìm và trả về thông tin liên hệ theo ID.
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return response()->json([
            'contact' => $contact
        ]);
    }

    // Xoá liên hệ
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'message' => 'Đã xoá liên hệ thành công.'
        ]);
    }
}
