<?php

namespace App\Models\Site;

use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 */
class Social extends UwtModel
{
    public $sortable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'icon', 'reference', 'sort'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Название',
            'icon' => 'Иконка',
            'reference' => 'Ссылка',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'reference' => 'required|string|max:255'
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Сайт / Социальные сети',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Сайт / Социальные сети',
                        'is_inner_card' => true,
                        'dataModel' => Social::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'Сайт / Социальные сети',
                'subTitle' => 'Добавить',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.iconpicker.fa5' => ['slug' => 'icon']],
                                ['interface.fields.simple' => ['slug' => 'reference']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Сайт / Социальные сети',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.iconpicker.fa5' => ['slug' => 'icon']],
                                ['interface.fields.simple' => ['slug' => 'reference']],
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
