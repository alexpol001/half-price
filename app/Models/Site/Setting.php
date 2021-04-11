<?php

namespace App\Models\Site;

use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 * @property string $politics
 * @property string $email
 */
class Setting extends UwtModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'politics', 'email'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Название',
            'politics' => 'Политика',
            'email' => 'Email',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'email' =>  'required|email|max:255',
            'politics' => 'required|string',
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => 'singleton',
            'create' => [
                'title' => 'Сайт / Настройки',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'email']],
                                ['interface.fields.editor.adminlte' => ['slug' => 'politics']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Сайт / Настройки',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'email']],
                                ['interface.fields.editor.adminlte' => ['slug' => 'politics']],
                            ]]
                        ]]
                    ]]
                ]
            ]
        ];
    }

    public function getAccess()
    {
        return [
            'index' => [1, 2],
            'create' => [1, 2],
            'update' => [1, 2],
        ];
    }
}
