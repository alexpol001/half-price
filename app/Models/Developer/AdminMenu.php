<?php

namespace App\Models\Developer;

use App\Http\Controllers\Admin\CrudController;
use App\Models\UwtModel;
use App\User;
use Illuminate\Support\Facades\Request;

/**
 * App\Models\Admin\AdminMenu
 *
 * @property int $id
 * @property string $path
 * @property string $title
 * @property string $icon
 * @property \App\Models\Developer\Model[] $models
 * @property \App\Models\Developer\AdminMenu $adminMenu
 * @property \App\Models\Developer\AdminMenu[] $adminMenus
 */
class AdminMenu extends UwtModel
{
    public $sortable = true;

    public function models()
    {
        return $this->belongsToMany('App\Models\Developer\Model', 'admin_menu_models', 'admin_menu_id', 'model_id');
    }

    public function adminMenu()
    {
        return $this->belongsTo('App\Models\Developer\AdminMenu');
    }

    public function adminMenus()
    {
        return $this->hasMany('App\Models\Developer\AdminMenu');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'path', 'icon', 'admin_menu_id', 'sort'
    ];

    public function getLabels()
    {
        return [
            'id' => 'Идентификатор',
            'title' => 'Название',
            'path' => 'Ссылка',
            'icon' => 'Иконка',
            'admin_menu_id' => 'Родитель',
            'models' => 'Модели'
        ];
    }

    public function store(array $attributes = [])
    {
        /** @var AdminMenu $store */
        $store = parent::store($attributes);
        if (isset($attributes['models'])) {
            $store->models()->sync($attributes['models']);
        }
        return $store;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $update = parent::update($attributes, $options);
        if (isset($attributes['models'])) {
            $this->models()->sync($attributes['models']);
        } else {
            $this->models()->sync([]);
        }
        return $update;
    }


    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'path' => 'string|max:255|nullable',
            'icon' => 'string|max:255'
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Разработка меню',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Роли пользователей' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Разработка меню',
                        'icon' => 'fas fa-cubes',
                        'is_inner_card' => true,
                        'dataModel' => AdminMenu::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title'],
                            ['data' => 'admin_menu_id', 'name' => 'adminMenu.title', 'orderable' => false, 'searchable' => false],
                            ['data' => 'path'],
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Разработка меню',
                'subTitle' => 'Добавить пункт меню ',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Разработка меню' => static::getController()::getFullRoute(AdminMenu::getInstance()),
                    'Добавить пункт меню' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.select2' => ['slug' => 'admin_menu_id', 'dataModel' => AdminMenu::getInstance()]],
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'path']],
                                ['interface.fields.select2' => ['slug' => 'models', 'dataModel' => Model::getInstance(), 'is_multiple' => true]],
                                ['interface.fields.iconpicker.fa5' => ['slug' => 'icon']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Разработка меню',
                'subTitle' => 'Редактировать пункт меню',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Разработка меню' => static::getController()::getFullRoute(AdminMenu::getInstance()),
                    'Редактировать пункт меню' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.select2' => ['slug' => 'admin_menu_id', 'dataModel' => AdminMenu::getInstance(), 'exclude' => 'id']],
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'path']],
                                ['interface.fields.select2' => ['slug' => 'models', 'dataModel' => Model::getInstance(), 'is_multiple' => true]],
                                ['interface.fields.iconpicker.fa5' => ['slug' => 'icon']],
                            ]]
                        ]]
                    ]]
                ]
            ]
        ];
    }

    public static function getMenu() {
        $currentPath = Request::getPathInfo();
        $topLevel = AdminMenu::query()->whereNull('admin_menu_id')->get();
        $menu = [];
        /** @var AdminMenu $item */
        foreach ($topLevel as $item) {
            if ($item->id == 1 && User::authUser()->userInfo->user_role_id != 1) {
                continue;
            }
            $menu[] = $item->getMenuItem($currentPath);
        }
        return $menu;
    }

    /**
     * @param $currentPath
     * @return array
     */
    private function getMenuItem($currentPath)
    {
        $active = false;
        if (!$this->icon || $this->icon == 'empty') {
            $this->icon = 'far fa-circle';
        }
        if ($crudModel = CrudController::getModel()) {
            if ($models = $this->models) {
                /** @var Model $model */
                foreach ($models as $model) {
                    if (class_exists($model->model)) {
                        if ($crudModel::getRoute() == $model->model::getRoute()) {
                            $active = true;
                            break;
                        }
                    }
                }
            }
        }
        $items = $this->getSubItems($currentPath);
        foreach ($items as $subItem) {
            if ($subItem['active']) {
                $active = true;
                break;
            }
        }
        $item = [
            'title' => $this->title,
            'path' => isset($this->path) ? $this->path : '#',
            'icon' => $this->icon,
            'active' => $active,
            'items' => $items,
            'visible' => true,
        ];

        if (($item['path'] == '#') && !count($item['items'])) {
            $item['visible'] = false;
        }

        return $item;
    }

    private function getSubItems($currentPath) {
        $items = [];
        if ($subItems = $this->adminMenus) {
            foreach ($subItems as $subItem) {
                $items[] = $subItem->getMenuItem($currentPath);
            }
        }
        return $items;
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
