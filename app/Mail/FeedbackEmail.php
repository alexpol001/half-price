<?php

namespace App\Mail;

use App\Models\Site\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $model;

    /**
     * Create a new message instance.
     *
     * @param $feedback
     */
    public function __construct($feedback)
    {
        $this->model = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $setting = Setting::query()->first();
        return $this->from($this->model->email, $setting->title)
            ->subject('Вопрос с сайта | '. $setting->title)
            ->view('mail.feedback.html')
            ->text('mail.feedback.text');
    }
}
