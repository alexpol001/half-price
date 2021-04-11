<?php

namespace App\Models\Products;

use App\Models\UwtModel;

/**
 * App\Models\Products\ProductCategory
 *
 * @property int $id
 * @property string $title
 * @property int $sort
 */
class ProductCategory extends UwtModel
{
    public $sortable = true;

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'product_category_id', 'id');
    }

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
                'title' => 'Продукты',
                'subTitle' => 'Категории',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Категории' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Категории',
                        'icon' => 'fas fa-th',
                        'is_inner_card' => true,
                        'dataModel' => ProductCategory::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title', 'orderable' => false],
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Продукты',
                'subTitle' => 'Добавить категорию',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Категории' => static::getController()::getFullRoute(ProductCategory::getInstance()),
                    'Добавить категорию' => 'active',
                ],
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
                'title' => 'Продукты',
                'subTitle' => 'Редактировать категорию',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Категории' => static::getController()::getFullRoute(ProductCategory::getInstance()),
                    'Редактировать категории' => 'active',
                ],
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
