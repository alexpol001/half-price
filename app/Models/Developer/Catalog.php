<?php

namespace App\Models\Developer;

use App\Helpers\CommonHelper;
use App\Models\UwtModel;

/**
 * App\Models\Developer\Catalog
 *
 * @property int $id
 * @property int $catalog_id
 * @property string $slug
 * @property string $title
 * @property string $path
 * @property Catalog catalog
 * @property Catalog[] catalogs
 */
class Catalog extends UwtModel
{
    protected $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'catalog_id', 'title', 'slug'
    ];

    protected $appends = [
        'path'
    ];

    public function catalog()
    {
        return $this->belongsTo('App\Models\Developer\Catalog');
    }

    public function catalogs()
    {
        return $this->hasMany('App\Models\Developer\Catalog');
    }

    public function model()
    {
        return $this->hasOne('App\Models\Developer\Model');
    }

    public function getLabels()
    {
        return [
            'id' => 'Идентификатор',
            'title' => 'Заголовок',
            'slug' => 'Название',
            'catalog_id' => 'Родительский каталог',
            'path' => 'Путь',
        ];
    }

    public function rules()
    {
        return [
            'catalog_id' => 'integer|nullable',
            'title' => 'required|string|max:255',
            'slug' => 'string|max:255|nullable',
        ];
    }

    public function generateAttributes()
    {
        return [
            'slug' => ['params' => [$this->slugSource], 'function' => function ($data) {
                $slug = CommonHelper::translate($data[$this->slugSource]);
                $slug = array_map('ucfirst', explode('-', $slug));
                return implode($slug);
            }],
        ];
    }

    public function getPathAttribute()
    {
        $catalog = $this;
        $pathParts = [];
        while ($catalog) {
            $pathParts[] = '/' . $catalog->slug;
            $catalog = $catalog->catalog;
        }
        $path = implode(array_reverse($pathParts));
        return $path;
    }

    public function getPages($params)
    {
        $closeRoute = static::getController()::getFullRoute(Model::getInstance());
        return [
            'create' => [
                'title' => 'Разработка моделей',
                'subTitle' => 'Добавить каталог',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Разработка моделей' => $closeRoute,
                    'Добавить каталог' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'closeRoute' => $closeRoute,
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
                'subTitle' => 'Редактировать каталог',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Разработка моделей' => $closeRoute,
                    'Редактировать каталог' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'closeRoute' => $closeRoute,
                        'components' => [
                            ['interface.tabs' => [
                                'items' => [
                                    [
                                        'title' => 'Основное', 'active' => true,
                                        'components' => [
                                            ['interface.card' => [
                                                'components' => [
                                                    ['interface.fields.simple' => ['slug' => 'slug']],
                                                    ['interface.fields.simple' => ['slug' => 'title']],
                                                    ['interface.fields.select2' => ['slug' => 'catalog_id', 'dataModel' => Catalog::getInstance(),
                                                        'filter' => ['id' => ['!=' => ['id' =>$this->getTree()]]],
                                                    ]]
                                                ]
                                            ]]
                                        ]
                                    ],
                                    [
                                        'title' => 'Модели', 'icon' => 'fas fa-cube',
                                        'components' => [
                                            ['interface.data-table' => [
                                                'title' => 'Список моделей текущего каталога',
                                                'icon' => 'fas fa-cubes',
                                                'is_inner_card' => true,
                                                'dataModel' => Model::getInstance(),
                                                'filter' => ['catalog_id' => ['=' => ['id' => [$this]]]],
                                                'deletable' => true,
                                                'columns' => [
                                                    ['data' => 'title'],
                                                    ['data' => 'slug'],
                                                ]
                                            ]]
                                        ]
                                    ],
                                    [
                                        'title' => 'Каталоги', 'icon' => 'fas fa-folder-open',
                                        'components' => [
                                            ['interface.data-table' => [
                                                'title' => 'Список каталогов текущего каталогов',
                                                'icon' => 'far fa-folder-open',
                                                'is_inner_card' => true,
                                                'dataModel' => Catalog::getInstance(),
                                                'filter' => ['catalog_id' => ['=' => ['id' => [$this]]]],
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
                        ]]
                    ]
                ]
            ],
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
