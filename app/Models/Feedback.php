<?php
namespace App\Models;

use App\Models\Site\SupportTheme;

class Feedback extends UwtModel
{
    public $theme;
    public $name;
    public $email;
    public $message;

    public function setAttr($attr) {
        $this->theme = $attr['theme'];
        $this->name = $attr['name'];
        $this->email = $attr['email'];
        $this->message = $attr['message'];
    }

    public function rules()
    {
        return [
            'theme' => 'required|exists:support_themes,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string'
        ];
    }

    public function getLabels()
    {
        return [
            'theme' => 'Выберите тему обращения',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'message' => 'Сообщение',
        ];
    }

    public function getPlaceHolders()
    {
        return [
            'message' => 'Напишите интересующий вас вопрос как можно более развернуто.',
        ];
    }

    public function getTheme() {
        return SupportTheme::find($this->theme);
    }
}
