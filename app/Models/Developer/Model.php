<?php

namespace App\Models\Developer;

use App\Helpers\CommonHelper;
use App\Models\UwtModel;

/**
 * App\Models\Developer\Model
 *
 * @property int $id
 * @property int $catalog_id
 * @property string $slug
 * @property string $title
 * @property Catalog catalog
 * @property string path
 * @property UwtModel model
 */
class Model extends UwtModel
{
    protected $slugSource = 'title';

    public function catalog()
    {
        return $this->belongsTo('App\Models\Developer\Catalog');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'catalog_id', 'title', 'slug'
    ];

    public function getLabels()
    {
        return [
            'id' => 'Идентификатор',
            'title' => 'Заголовок',
            'slug' => 'Модель',
            'catalog_id' => 'Каталог',
        ];
    }

    public function getPlaceHolders()
    {
        return [
            'slug' => 'Имя файла модели (будет создано автоматически)',
        ];
    }

    public function rules()
    {
        return [
            'catalog_id' => 'integer|nullable',
            'title' => 'required|string|max:255',
            'slug' => 'string|max:255|nullable|unique:models,slug,' . $this->id,
        ];
    }

    public function generateAttributes()
    {
        return [
            'slug' => ['params' => [$this->slugSource], 'function' => function ($data) {
                $slug = CommonHelper::translate($data[$this->slugSource]);
                $slug = array_map('ucfirst', explode('-', $slug));
                return implode($slug);
            }]
        ];
    }

    public function getPathAttribute()
    {
        $path = '';
        if ($catalog = $this->catalog) {
            $path .= $catalog->path;
        }
        $path .= '/'.$this->slug;
        return $path;
    }

    public function getModelAttribute() {
        return "App\\Models" . str_replace('/', '\\', $this->path);
    }

    public function getPages($params) {
        return [
            'index' => [
                'title' => 'Разработка моделей',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Разработка моделей' => 'active'],
                'components' => [
                    ['interface.tabs' => [
                        'items' => [
                            [
                                'title' => 'Модели', 'icon' => 'fas fa-cube', 'active' => true,
                                'components' => [
                                    ['interface.data-table' => [
                                        'title' => 'Список всех моделей',
                                        'icon' => 'fas fa-cubes',
                                        'is_inner_card' => true,
                                        'dataModel' => Model::getInstance(),
                                        'deletable' => true,
                                        'columns' => [
                                            ['data' => 'title'],
                                            ['data' => 'slug'],
                                            ['data' => 'catalog_id', 'name' => 'catalog.path', 'searchable' => false, 'orderable' => false,],
                                        ]
                                    ]]
                                ]
                            ],
                            [
                                'title' => 'Каталоги', 'icon' => 'fas fa-folder-open',
                                'components' => [
                                    ['interface.data-table' => [
                                        'title' => 'Список всех каталогов',
                                        'icon' => 'far fa-folder-open',
                                        'is_inner_card' => true,
                                        'dataModel' => Catalog::getInstance(),
                                        'deletable' => true,
                                        'columns' => [
                                            ['data' => 'title'],
                                            ['data' => 'path', 'name' => 'path', 'searchable' => false, 'orderable' => false],
                                        ]
                                    ]]
                                ]
                            ]
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Разработка моделей',
                'subTitle' => 'Добавить модель',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Разработка моделей' => static::getController()::getFullRoute(Model::getInstance()),
                    'Добавить модель' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'slug']],
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.select2' => ['slug' => 'catalog_id', 'dataModel' => Catalog::getInstance()]]
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Разработка моделей',
                'subTitle' => 'Редактировать модель',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Разработка моделей' => static::getController()::getFullRoute(Model::getInstance()),
                    'Редактировать модель' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [
                            ['interface.tabs' => [
                                'items' => [
                                    [
                                        'title' => 'Основное', 'active' => true,
                                        'components' => [
                                            ['interface.fields.simple' => ['slug' => 'slug']],
                                            ['interface.fields.simple' => ['slug' => 'title']],
                                            ['interface.fields.select2' => ['slug' => 'catalog_id', 'dataModel' => Catalog::getInstance()]]
                                        ]
                                    ],
                                    [
                                        'title' => 'Атрибуты',
                                        'content' => [

                                        ]
                                    ]
                                ]
                            ]]
                        ]]
                    ]
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
