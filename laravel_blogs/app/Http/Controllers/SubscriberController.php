<?php
namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        Subscriber::create([
            'email' => $request->email,
        ]);

        return redirect()->route('subscribe')->with('success', 'Đăng ký nhận thông báo thành công!');
    }

    public function unsubscribe($email)
    {
        $email = base64_decode($email);
        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            $subscriber->delete();
            return view('unsubscribe', ['message' => 'Bạn đã hủy đăng ký thành công!']);
        }

        return view('unsubscribe', ['message' => 'Email không tồn tại trong danh sách đăng ký.']);
    }
}
