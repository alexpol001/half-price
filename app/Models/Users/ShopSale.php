<?php

namespace App\Models\Users;

use App\Models\Client\Net;
use App\Models\Developer\CropImage;
use App\Models\UwtModel;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Users\Shop
 *
 * @property int $id
 * @property int shop_id
 * @property int sale
 * @property CropImage crop
 * @property Shop shop
 */
class ShopSale extends UwtModel
{
    public function shop()
    {
        return $this->belongsTo('App\Models\Users\Shop');
    }

    public function code()
    {
        return $this->belongsTo('App\Models\Developer\CropImage', 'code_crop_image_id', 'id');
    }

    public function getFields()
    {
        return [
            'code' => ['slug' => 'code', 'type' => 'cropImage', 'mimes' => 'jpeg, jpg, png, gif', 'size' => ['width' => 320, 'height' => 240]],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id', 'sale'
    ];


    public function getLabels()
    {
        return [
            'sale' => 'Процент скидки (от 50)',
            'code' => 'Штрихкод',
        ];
    }

    public function getPlaceHolders()
    {
        return [
            'sale' => 'Укажите % скидки'
        ];
    }

    public function rules()
    {
        return [
            'shop_id' => 'required_if:id,' . $this->id.'|exists:shops,id',
            'sale' => 'required|integer|min:50|max:100',
            'code' => 'mimes:jpeg,jpg,png,gif|required_if:id,' . $this->id,
        ];
    }

    public function getPages($params)
    {
        $isAdmin = true;
        if (isset($params['relation']) && isset($params['id'])) {
            $relation = $params['relation'];
            /** @var Shop $relation */
            $relation = $this->$relation()->orWhere('id', $params['id'])->first();
        } else {
            if (($user = User::authUser())) {
                $relation = $this->shop;
                if (($user->userInfo->userRole->id == 4 || $user->userInfo->userRole->id == 3) && $shop = $user->userInfo->shop) {
                    $relation = $shop;
                    $isAdmin = false;
                }
            }
        }
        if (!$relation) return [];
        $closeRoute = static::getController()::getFullRoute(UserInfo::getInstance()).'/update/'.$relation->userInfo->id;
        return [
            'index' => [
                'breadcrumbs' => [
                    'Главная' => '/',
                    'Скидки' => 'active',
                ],
                'title' => 'Скидки',
                'cabinetActive' => 'sales',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Скидки магазина',
                        'icon' => 'fas fa-tags',
                        'is_inner_card' => true,
                        'dataModel' => ShopSale::getInstance(),
                        'deletable' => true,
                        'filter' => ['shop_id' => ['=' => ['id' => [$relation]]]],
                        'columns' => [
                            ['data' => 'sale']
                        ]
                    ]]
                ]
            ],
            'create' => $isAdmin ? [
                'title' => 'Скидки магазина',
                'subTitle' => 'Добавить скидку',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Пользователи' => static::getController()::getFullRoute(UserInfo::getInstance()),
                    'Магазин'.(isset($relation) ? ' - '.$relation->userInfo->user->email: '') => $closeRoute,
                    'Добавить скидку' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'closeRoute' => $closeRoute,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'sale']],
                                ['interface.fields.file.image' => $this->getFields()['code']],
                            ]]
                        ]]
                    ]]
                ]
            ] : $this->getCreatePage(),
            'update' => $isAdmin ? [
                'title' => 'Скидки магазина',
                'subTitle' => 'Редактировать скидку',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Пользователи' => static::getController()::getFullRoute(UserInfo::getInstance()),
                    'Магазин'.(isset($relation) ? ' - '.$relation->userInfo->user->email: '') => $closeRoute,
                    'Редактировать скидку' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'closeRoute' => $closeRoute,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'sale']],
                                ['interface.fields.file.image' => $this->getFields()['code']],
                            ]]
                        ]]
                    ]]
                ]
            ] : $this->getUpdatePage()
        ];
    }

    public function getCreatePage() {
        $closeRoute = static::getController()::getFullRoute(ShopSale::getInstance());
        return [
            'title' => 'Скидки / Добавить',
            'cabinetActive' => 'sales',
            'breadcrumbs' => [
                'Главная' => '/',
                'Скидки' => $closeRoute,
                'Добавить' => 'active',
            ],
            'components' => [
                ['interface.form' => [
                    'files' => true,
                    'closeRoute' => $closeRoute,
                    'components' => [
                        ['interface.fields.simple' => ['slug' => 'sale']],
                        ['interface.fields.file.image' => $this->getFields()['code']],
                    ]
                ]]
            ]
        ];
    }

    public function getUpdatePage() {
        $closeRoute = static::getController()::getFullRoute(ShopSale::getInstance());
        return [
            'title' => 'Скидки / Редактировать',
            'cabinetActive' => 'sales',
            'breadcrumbs' => [
                'Главная' => '/',
                'Скидки' => $closeRoute,
                'Редактировать' => 'active',
            ],
            'components' => [
                ['interface.form' => [
                    'files' => true,
                    'closeRoute' => $closeRoute,
                    'components' => [
                        ['interface.fields.simple' => ['slug' => 'sale']],
                        ['interface.fields.file.image' => $this->getFields()['code']],
                    ]
                ]]
            ]
        ];
    }

    public function generateAttributes()
    {
        return [
            'shop_id' => ['isOnlyCreate' => true, 'function' => function ($data) {
                return User::authUser()->userInfo->shop->id;
            }]
        ];
    }

    public function getAccess()
    {
        return [
            'index' => [1, 2, 3, 4],
            'create' => [1, 2, 3, 4],
            'update' => [1, 2, 3, 4],
        ];
    }
}
