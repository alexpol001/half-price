<?php

namespace App\Models\Materials\Placement;

use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 */
class Condition extends UwtModel
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
            'title' => 'Заголовок',
            'description' => 'Описание',
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
                'title' => 'Размещение / Условия',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Условия',
                        'is_inner_card' => true,
                        'dataModel' => Condition::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'Размещение / Условия',
                'subTitle' => 'Добавить',
                'components' => [
                    ['interface.form' => [
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
                'title' => 'Размещение / Условия',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
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
