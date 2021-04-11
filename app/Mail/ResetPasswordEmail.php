<?php

namespace App\Mail;

use App\Models\Site\Setting;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordEmail extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $setting = Setting::query()->first();
        return (new MailMessage)
            ->from($setting->email,  $setting->title)
            ->markdown('mail.notification')
            ->subject(Lang::getFromJson('Восстановление пароля | '.$setting->title))
            ->line(Lang::getFromJson('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.'))
            ->action(Lang::getFromJson('Сбросить пароль'), url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line(Lang::getFromJson('Срок действия ссылки для сброса пароля истекает через :count минут.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.'));
    }
}
