<?php

namespace App\Models\Users;

use App\Models\UwtModel;

/**
 * App\Models\Developer\Model
 *
 * @property int $id
 * @property string $title
 * @property userRole[] $userRoles
 * @property userRole[] $childUserRoles
 */
class UserRole extends UwtModel
{
    public function userRoles()
    {
        return $this->belongsToMany('App\Models\Users\UserRole', 'user_access_roles', 'user_role_id', 'user_role_id_belong');
    }

    public function childUserRoles() {
        return $this->belongsToMany('App\Models\Users\UserRole', 'user_access_roles', 'user_role_id_belong', 'user_role_id');
    }

    public function store(array $attributes = [])
    {
        /** @var UserRole $store */
        $store = parent::store($attributes);
        if (isset($data['userRoles'])) {
            $store->userRoles()->sync($attributes['userRoles']);
        }
        return $store;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $update = parent::update($attributes, $options);
        if (isset($attributes['userRoles'])) {
            $this->userRoles()->sync($attributes['userRoles']);
        } else {
            $this->userRoles()->sync([]);
        }
        return $update;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    public function getLabels()
    {
        return [
            'id' => 'Идентификатор',
            'title' => 'Название',
            'userRoles' => 'Открыт для'
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
        ];
    }

    public function getPages($params) {
        return [
            'index' => [
                'title' => 'Пользователи',
                'subTitle' => 'Роли пользователей',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Роли пользователей' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Роли пользователей',
                        'icon' => 'fas fa-cubes',
                        'is_inner_card' => true,
                        'dataModel' => UserRole::getInstance(),
                        'deletable' => true,
                        'columns' => [
                            ['data' => 'title'],
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Пользователи',
                'subTitle' => 'Добавить роль',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Роли пользователей' => static::getController()::getFullRoute(UserRole::getInstance()),
                    'Добавить роль' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.select2' => ['slug' => 'userRoles', 'dataModel' => UserRole::getInstance(), 'is_multiple' => true]],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Пользователи',
                'subTitle' => 'Редактировать роль',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Роли пользователей' => static::getController()::getFullRoute(UserRole::getInstance()),
                    'Редактировать роль' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.select2' => ['slug' => 'userRoles', 'dataModel' => UserRole::getInstance(), 'is_multiple' => true]],
                            ]]
                        ]]
                    ]]
                ]
            ]
        ];
    }

    public function getFullRoles($excludeSelf = false) {
        $roles = [];
        if (!$excludeSelf) {
            $roles = [$this];
        }
        foreach ($this->childUserRoles as $role) {
            $isExist = false;
            foreach ($roles as $existRole) {
                if ($role->id == $existRole->id) {
                    $isExist = true;
                    break;
                }
            }
            if (!$isExist) {
                $roles = array_merge($roles, $role->getFullRoles());
            }
        }
        return $roles;
    }

    /**
     * @param userRole $userRole
     * @return bool
     */
    public function hasAccess($userRole) {
        foreach ($this->getFullRoles(true) as $role) {
            if ($role->id == $userRole->id) {
                return true;
            }
        }
        return false;
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
