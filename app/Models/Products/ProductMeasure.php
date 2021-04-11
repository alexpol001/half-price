<?php

namespace App\Models\Products;

use App\Models\UwtModel;

/**
 * App\Models\Client\Net
 *
 * @property int $id
 * @property string $title
 * @property int $sort
 */
class ProductMeasure extends UwtModel
{
    public $sortable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'short_title', 'title', 'sort'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Название',
            'short_title' => 'Сокращение',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'short_title' => 'required|string|max:255'
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Продукты',
                'subTitle' => 'Единицы измерения',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Единицы измерения' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Единицы измерения',
                        'icon' => 'fas fa-weight',
                        'is_inner_card' => true,
                        'dataModel' => ProductMeasure::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title', 'sortable' => true],
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Продукты',
                'subTitle' => 'Добавить единицу измерения',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Единицы измерения' => static::getController()::getFullRoute(ProductMeasure::getInstance()),
                    'Добавить единицу измерения' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'short_title']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Продукты',
                'subTitle' => 'Редактировать единицу измерения',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Единицы измерения' => static::getController()::getFullRoute(ProductMeasure::getInstance()),
                    'Редактировать единицу измерения' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'short_title']],
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
            'index' => [1],
            'create' => [1],
            'update' => [1],
        ];
    }
}
