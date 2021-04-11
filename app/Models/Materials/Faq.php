<?php

namespace App\Models\Materials;

use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 */
class Faq extends UwtModel
{
    public $sortable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'sort'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Вопрос',
            'description' => 'Ответ',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Помощь',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Помощь',
                        'is_inner_card' => true,
                        'dataModel' => Faq::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'Помощь',
                'subTitle' => 'Добавить',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.textarea' => ['slug' => 'description']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Помощь',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.textarea' => ['slug' => 'description']],
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
