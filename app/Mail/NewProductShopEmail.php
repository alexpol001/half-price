<?php

namespace App\Mail;

use App\Models\Site\Setting;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class NewProductShopEmail extends Notification
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        $setting = Setting::query()->first();
        return (new MailMessage)
            ->from($setting->email,  $setting->title)
            ->markdown('mail.notification')
            ->subject(Lang::getFromJson('Новый продукт магазина | '.$setting->title))
            ->line(Lang::getFromJson('Вы получили это письмо потому что магазин добавил новый продукт в свой кабинет.'))
            ->line(Lang::getFromJson('Вам необходимо проверить правильность введенных данных и активировать продукт для публикации на сайте.'))
            ->action(Lang::getFromJson('Проверить'), url(config('app.url').'/admin/users/shop-product/update/'.$this->id, false));
    }
}
