<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(Lang::get('Đặt lại mật khẩu'))
            ->line(Lang::get('Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.'))
            ->action(Lang::get('Đặt lại mật khẩu'), $url)
            ->line(Lang::get('Liên kết này sẽ hết hạn trong :count phút.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::get('Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.'));
    }
}
