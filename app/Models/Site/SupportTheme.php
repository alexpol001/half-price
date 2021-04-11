<?php

namespace App\Models\Site;

use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 */
class SupportTheme extends UwtModel
{
    public $sortable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'sort'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Название',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Сайт / Темы сообщений',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Сайт / Темы сообщений',
                        'is_inner_card' => true,
                        'dataModel' => SupportTheme::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'Сайт / Темы сообщений',
                'subTitle' => 'Добавить',
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Сайт / Темы сообщений',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
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
