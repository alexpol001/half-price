<?php

namespace App\Models\Users;

use App\Models\Client\Net;
use App\Models\UwtModel;
use App\User;
use Illuminate\Support\Facades\Hash;

/**
 * App\Models\Developer\Model
 *
 * @property int $id
 * @property int $user_role_id
 * @property UserRole $userRole
 * @property User $user
 * @property Shop $shop
 */
class UserInfo extends UwtModel
{
    protected $fillable = [
        'user_role_id',
    ];

    public function userRole()
    {
        return $this->belongsTo('App\Models\Users\UserRole');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function shop()
    {
        return $this->hasOne('App\Models\Users\Shop');
    }

    public function getLabels()
    {
        return [
            'id' => 'Идентификатор',
            'user_id' => 'Пользователь',
            'user_role_id' => 'Статус',
            'user_name' => 'Имя',
            'user_email' => 'Email',
            'password' => 'Пароль',
            'repeat_password' => 'Повторите пароль',

            'shop_net_id' => 'Сеть',
            'shop_city' => 'Город',
            'shop_street' => 'Улица',
            'shop_house' => 'Дом',
            'shop_phone' => 'Телефон',
        ];
    }

    public function store(array $attributes = [])
    {
        /** @var UserInfo $model */
        $model = parent::store($attributes);
        if ($model->id) {
            $model->user()->create([
                'email' => $attributes['user_email'],
                'name' => $attributes['user_name'],
                'password' => Hash::make($attributes['password']),
            ]);
        }
        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $update = parent::update($attributes, $options);
        if ($update) {
            $this->user()->update([
                'email' => $attributes['user_email'],
                'name' => $attributes['user_name'],
                'password' => $attributes['password'] ? Hash::make($attributes['password']) : $this->user->password,
            ]);
            if ($this->userRole->id == 3 || $this->userRole->id == 4) {
                $shopData['net_id'] = isset($attributes['shop_net_id']) ? $attributes['shop_net_id'] : null;
                $shopData['city'] = isset($attributes['shop_net_id']) ? $attributes['shop_city'] : null;
                $shopData['street'] = isset($attributes['shop_net_id']) ? $attributes['shop_street'] : null;
                $shopData['house'] = isset($attributes['shop_house']) ? $attributes['shop_house'] : null;
                $shopData['phone'] = isset($attributes['shop_phone']) ? $attributes['shop_phone'] : null;
                if ($shopData['net_id'] && $shopData['city'] && $shopData['street'] && $shopData['house'] && $shopData['phone']) {
                    if ($this->shop) {
                        $this->shop()->update($shopData);
                    } else {
                        $this->shop()->create($shopData);
                    }
                }
            }
        }
        return $update;
    }

    public function rules()
    {
        $rules = [
            'user_role_id' => 'required|integer',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|string|max:255|unique:users,email,' . $this->id . ',user_info_id',
            'password' => 'string|min:8|nullable|required_if:id,' . $this->id,
            'repeat_password' => 'same:password',
        ];
        /** @var UserInfo $model */
        if ($model = $this->getDataBaseModel()) {
            if ($model->userRole->id == 3 || $model->userRole->id == 4) {
                $rules = array_merge($rules, [
                    'shop_net_id' => 'exists:nets,id|required_if:user_role_id,4,3|nullable',
                    'shop_city' => 'string|max:255|required_if:user_role_id,4,3|nullable',
                    'shop_street' => 'string|max:255|required_if:user_role_id,4,3|nullable',
                    'shop_house' => 'string|max:255|required_if:user_role_id,4,3|nullable',
                    'shop_phone' => 'regex:/^\+7\s\([0-9]{3}\)\s[0-9]{3}-[0-9]{2}-[0-9]{2}$/|required_if:user_role_id,4,3|nullable',
                ]);
            }
        }
        return $rules;
    }

    protected static function getLockRoles()
    {
        $query = UserRole::all();
        $roles = [];
        foreach ($query as $item) {
            $roles[] = $item;
        }
        if ($user = User::authUser()) {
            $userRole = $user->userInfo->userRole;
            foreach ($roles as $key => $role) {
                foreach ($userRole->getFullRoles(!$userRole->hasAccess($userRole)) as $item) {
                    if ($role->id == $item->id) {
                        unset($roles[$key]);
                    }
                }
            }
        }
        return $roles;
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Пользователи',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Роли пользователей' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Пользователи',
                        'icon' => 'fas fa-users',
                        'is_inner_card' => true,
                        'dataModel' => UserInfo::getInstance(),
                        'deletable' => true,
                        'filter' => ['user_role_id' => ['!=' => ['id' => self::getLockRoles()]]],
                        'columns' => [
                            ['data' => 'user_id', 'name' => 'user.email'],
                            ['data' => 'user_role_id', 'name' => 'userRole.title']
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Пользователи',
                'subTitle' => 'Добавить пользователя',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Пользователи' => static::getController()::getFullRoute(UserInfo::getInstance()),
                    'Добавить пользователя' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.tabs' => [
                            'items' => [
                                [
                                    'title' => 'Основное', 'active' => true,
                                    'components' => [
                                        ['interface.fields.simple' => ['slug' => 'user_name']],
                                        ['interface.fields.simple' => ['slug' => 'user_email']],
                                        ['interface.fields.password' => ['slug' => 'password']],
                                        ['interface.fields.password' => ['slug' => 'repeat_password']],
                                        ['interface.fields.select2' => ['slug' => 'user_role_id', 'dataModel' => UserRole::getInstance(), 'default' => 4,
                                            'filter' => ['id' => ['!=' => ['id' => self::getLockRoles()]]],
                                        ]],
                                    ]]
                            ]
                        ]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Пользователи',
                'subTitle' => 'Редактировать пользователя',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Пользователи' => static::getController()::getFullRoute(UserInfo::getInstance()),
                    'Редактировать пользователя' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'components' => [['interface.tabs' => [
                            'items' => [
                                [
                                    'title' => 'Основное', 'active' => true,
                                    'components' => [
                                        ['interface.fields.simple' => ['slug' => 'user_name']],
                                        ['interface.fields.simple' => ['slug' => 'user_email']],
                                        ['interface.fields.password' => ['slug' => 'password']],
                                        ['interface.fields.password' => ['slug' => 'repeat_password']],
                                        ['interface.fields.select2' => ['slug' => 'user_role_id', 'dataModel' => UserRole::getInstance(),
                                            'filter' => ['id' => ['!=' => ['id' => self::getLockRoles()]]],
                                            'isRender' => User::authUser() && (User::authUser()->userInfo->userRole->hasAccess(User::authUser()->userInfo->userRole) || User::authUser()->userInfo->id != $this->id),],
                                        ],
                                    ]
                                ],
                                [
                                    'title' => 'Данные магазина', 'isRender' => $this->userRole && ($this->userRole->id == 3 || $this->userRole->id == 4),
                                    'components' => [
                                        ['interface.fields.select2' => ['slug' => 'shop_net_id', 'dataModel' => Net::getInstance()]],
                                        ['interface.fields.mask' => ['slug' => 'shop_phone', 'icon' => 'fas fa-phone', 'mask' => '+7 (999) 999-99-99']],
                                        ['interface.fields.address.russia-csh-map' => [
                                            'id' => 'address-shop',
                                            'citySlug' => 'shop_city',
                                            'streetSlug' => 'shop_street',
                                            'houseSlug' => 'shop_house'
                                        ]]
                                    ]
                                ],
                                [
                                    'title' => 'Скидки магазина', 'isRender' => $this->userRole && ($this->userRole->id == 3 || $this->userRole->id == 4) && $this->shop,
                                    'components' => [
                                        ['interface.data-table' => [
                                            'title' => 'Скидки магазина',
                                            'icon' => 'fas fa-barcode',
                                            'is_inner_card' => true,
                                            'dataModel' => ShopSale::getInstance(),
                                            'deletable' => true,
                                            'filter' => ['shop_id' => ['=' => ['id' => [$this->shop]]]],
                                            'relation' => $this->shop ? '/shop/'.$this->shop->id : null,
                                            'columns' => [
                                                ['data' => 'sale']
                                            ]
                                        ]]
                                    ]
                                ],
                                [
                                    'title' => 'Товары магазина', 'isRender' => $this->userRole && ($this->userRole->id == 3 || $this->userRole->id == 4) && $this->shop,
                                    'components' => [
                                        ['interface.data-table' => [
                                            'title' => 'Товары',
                                            'icon' => 'fas fa-shopping-bag',
                                            'is_inner_card' => true,
                                            'dataModel' => ShopProduct::getInstance(),
                                            'deletable' => true,
                                            'filter' => ['shop_id' => ['=' => ['id' => [$this->shop]]]],
                                            'relation' => $this->shop ? '/shop/'.$this->shop->id : null,
                                            'columns' => [
                                                ['data' => 'product_id', 'name' => 'product.title', 'orderable' => false],
                                                ['data' => 'shop_sale_id', 'name' => 'shopSale.sale', 'orderable' => false],
                                                ['data' => 'over_date', 'name' => 'mutator.date', 'orderable' => false, 'searchable' => false],
                                                ['data' => 'active', 'name' => 'mutator.active_text']
                                            ]
                                        ]]
                                    ]
                                ]
                            ]
                        ]
                        ]]
                    ]]
                ]
            ]
        ];
    }

    public function getShopNetIdAttribute() {
        return $this->shop ? $this->shop->net_id : null;
    }

    public function beforeSave($insert, $attributes)
    {
        /** @var UserInfo $editUser */
        $editUser = UserInfo::find($this->id);
        $editUser = $editUser ? $editUser : $this;
        if ($user = User::authUser()) {
            $userRole = $user->userInfo->userRole;
            if (!$userRole->hasAccess($this->userRole) || !$userRole->hasAccess($editUser->userRole)) {
                return false;
            }
        }
        return parent::beforeSave($insert, $attributes);
    }

    public function afterSave($insert, $attributes)
    {
        return parent::afterSave($insert, $attributes); // TODO: Change the autogenerated stub
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
