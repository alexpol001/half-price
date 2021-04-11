<?php

namespace App\Models\Users;

use App\Helpers\CommonHelper;
use App\Mail\NewProductShopEmail;
use App\Models\Client\Net;
use App\Models\Product;
use App\Models\Products\ProductMeasure;
use App\Models\UwtModel;
use App\User;

/**
 * App\Models\Users\Shop
 *
 * @property int $id
 * @property int $shop_id
 * @property int $product_id
 * @property int $shop_sale_id
 * @property int $over_date
 * @property int $product_life
 * @property int $active
 * @property Shop $shop
 * @property ShopSale $shopSale
 * @property Product $product
 */
class ShopProduct extends UwtModel
{

    public function getFields()
    {
        return [
            'over_date' => ['slug' => 'over_date', 'type' => 'dateRange'],
        ];
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function shopSale()
    {
        return $this->belongsTo('App\Models\Users\ShopSale');
    }

    public function shop()
    {
        return $this->belongsTo('App\Models\Users\Shop');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id', 'product_id', 'shop_sale_id', 'over_date', 'product_life', 'price', 'active'
    ];

    public function rules()
    {
        return [
            'shop_id' => 'required_if:id,' . $this->id.'|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'shop_sale_id' => 'exists:shop_sales,id|nullable',
            'over_date' => 'regex:/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}\s-\s[0-9]{2}:[0-9]{2}$/|nullable',
            'product_life' => 'integer|min:0|nullable',
            'price' => 'numeric|nullable'
        ];
    }


    public function getLabels()
    {
        return [
            'product_id' => 'Продукт',
            'shop_sale_id' => 'Скидка',
            'over_date' => 'Дата окончания акции',
            'product_life' => 'Время до просрочки продукта (в днях)',
            'price' => 'Цена без скидки',
            'active' => 'Прошел проверку',
        ];
    }

    public function getPlaceHolders()
    {
        return [
            'product_id' => 'Выберите продукт',
            'over_date' => 'Постоянно (по умолчанию)'
        ];
    }

    public function getPages($params)
    {
        $isAdmin = true;
        $relation = false;
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
        $closeRoute = static::getController()::getFullRoute(UserInfo::getInstance()) . '/update/' . $relation->userInfo->id;
        return [
            'index' => [
                'breadcrumbs' => [
                    'Главная' => '/',
                    'Товары' => 'active',
                ],
                'title' => 'Скидки',
                'cabinetActive' => 'products',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Товары магазина',
                        'icon' => 'fas fa-tags',
                        'is_inner_card' => true,
                        'dataModel' => ShopProduct::getInstance(),
                        'deletable' => true,
                        'filter' => ['shop_id' => ['=' => ['id' => [User::authUser()->userInfo->shop]]]],
                        'columns' => [
                            ['data' => 'product_id', 'name' => 'product.title', 'orderable' => false],
                            ['data' => 'price'],
                            ['data' => 'shop_sale_id', 'name' => 'shopSale.sale', 'orderable' => false],
                            ['data' => 'over_date', 'name' => 'mutator.date', 'orderable' => false, 'searchable' => false],
                        ]
                    ]]
                ]
            ],
            'create' => $isAdmin ? [
                'title' => 'Товары магазина',
                'subTitle' => 'Добавить товар',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Пользователи' => static::getController()::getFullRoute(UserInfo::getInstance()),
                    'Магазин' . (isset($relation) ? ' - ' . $relation->userInfo->user->email : '') => $closeRoute,
                    'Добавить скидку' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'closeRoute' => $closeRoute,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.select2' => ['slug' => 'product_id', 'dataModel' => Product::getInstance()]],
                                ['interface.fields.simple' => ['slug' => 'price']],
                                ['interface.fields.select2' => ['slug' => 'shop_sale_id', 'dataModel' => ShopSale::getInstance(), 'dataTitle' => 'sale',
                                    'filter' => ['shop_id' => ['=' => ['id' => [$relation]]]],
                                ]],
                                ['interface.fields.datepicker' => ['slug' => 'over_date']],
                                ['interface.fields.simple' => ['slug' => 'product_life']],
                                ['interface.fields.check' => ['slug' => 'active']]
                            ]]
                        ]]
                    ]]
                ]
            ] : $this->getCreatePage($relation),
            'update' => $isAdmin ? [
                'title' => 'Товары магазина',
                'subTitle' => 'Редактировать товар',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Пользователи' => static::getController()::getFullRoute(UserInfo::getInstance()),
                    'Магазин' . (isset($relation) ? ' - ' . $relation->userInfo->user->email : '') => $closeRoute,
                    'Редактировать скидку' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'closeRoute' => $closeRoute,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.select2' => ['slug' => 'product_id', 'dataModel' => Product::getInstance()]],
                                ['interface.fields.simple' => ['slug' => 'price']],
                                ['interface.fields.select2' => ['slug' => 'shop_sale_id', 'dataModel' => ShopSale::getInstance(), 'dataTitle' => 'sale',
                                    'filter' => ['shop_id' => ['=' => ['id' => [$relation]]]],
                                ]],
                                ['interface.fields.datepicker' => ['slug' => 'over_date']],
                                ['interface.fields.simple' => ['slug' => 'product_life']],
                                ['interface.fields.check' => ['slug' => 'active']]
                            ]]
                        ]]
                    ]]
                ]
            ] : $this->getUpdatePage($relation)
        ];
    }

    public function beforeSave($insert, $attributes)
    {
        foreach ($this->getFields() as $field) {
            $slug = $field['slug'];
            switch ($field['type']) {
                case 'dateRange':
                    $this->over_date = CommonHelper::dateToTimeStamp($attributes[$slug]);
            }
        }
        return parent::beforeSave($insert, $attributes); // TODO: Change the autogenerated stub
    }


    public function getCreatePage($relation) {
        $closeRoute = static::getController()::getFullRoute(ShopProduct::getInstance());
        return [
            'title' => 'Товары / Добавить',
            'breadcrumbs' => [
                'Главная' => '/',
                'Товары' => $closeRoute,
                'Добавить' => 'active',
            ],
            'cabinetActive' => 'products',
            'components' => [
                ['interface.form' => [
                    'files' => true,
                    'closeRoute' => $closeRoute,
                    'components' => [
                        ['interface.required-text' => []],
                        ['interface.fields.select2' => [
                            'slug' => 'product_id',
                            'dataModel' => Product::getInstance(),
                            'addColumn' => 'code',
                            'search' => 'Начните писать, чтобы искать продукт',
                            'modal' => [
                            'components' => [
                                ['interface.ajaxform' => [
                                    'model' => Product::getInstance(),
                                    'files' => true,
                                    'components' => [
                                        ['interface.required-text' => []],
                                        ['interface.fields.select2' => ['slug' => 'product_category_id', 'dataModel' => \App\Models\Products\ProductCategory::getInstance(), 'addColumn' => null, 'length' => 100]],
                                        ['interface.fields.simple' => ['slug' => 'code']],
                                        ['interface.fields.simple' => ['slug' => 'title']],
                                        ['interface.fields.file.image' => Product::getInstance()->getFields()['logo']],
                                        ['interface.fields.textarea' => ['slug' => 'description']],
                                        ['interface.build.row' => [
                                            'components' => [
                                                ['interface.build.div' => [
                                                    'class' => 'col-sm-6',
                                                    'components' => [
                                                        ['interface.fields.simple' => ['slug' => 'measure_value', 'hint' => 'Пример: 0.25']],
                                                    ]
                                                ]],
                                                ['interface.build.div' => [
                                                    'class' => 'col-sm-6',
                                                    'components' => [
                                                        ['interface.fields.select2' => ['slug' => 'product_measure_id', 'dataModel' => ProductMeasure::getInstance(), 'addColumn' => null, 'selected' => true]],
                                                    ]
                                                ]],
                                            ]
                                        ]],
                                    ]
                                ]],
                        ]]
                        ]],
                        ['interface.fields.simple' => ['slug' => 'price', 'hint' => 'Пример: 999.99']],
                        ['interface.fields.select2' => ['slug' => 'shop_sale_id', 'dataModel' => ShopSale::getInstance(), 'dataTitle' => 'sale',
                            'filter' => ['shop_id' => ['=' => ['id' => [$relation]]]],
                        ]],
                        ['interface.fields.datepicker' => ['slug' => 'over_date']],
                        ['interface.fields.simple' => ['slug' => 'product_life']],
                    ]
                ]]
            ]
        ];
    }

    public function getUpdatePage($relation) {
        $closeRoute = static::getController()::getFullRoute(ShopProduct::getInstance());
        return [
            'title' => 'Товары / Редактировать',
            'cabinetActive' => 'products',
            'breadcrumbs' => [
                'Главная' => '/',
                'Товары' => $closeRoute,
                'Редактировать' => 'active',
            ],
            'components' => [
                ['interface.form' => [
                    'files' => true,
                    'closeRoute' => $closeRoute,
                    'components' => [
                        ['interface.required-text' => []],
                        ['interface.fields.select2' => [
                            'slug' => 'product_id',
                            'dataModel' => Product::getInstance(),
                        ]],
                        ['interface.fields.simple' => ['slug' => 'price', 'hint' => 'Формат: 999.99']],
                        ['interface.fields.select2' => ['slug' => 'shop_sale_id', 'dataModel' => ShopSale::getInstance(), 'dataTitle' => 'sale',
                            'filter' => ['shop_id' => ['=' => ['id' => [$relation]]]],
                        ]],
                        ['interface.fields.datepicker' => ['slug' => 'over_date']],
                        ['interface.fields.simple' => ['slug' => 'product_life']],
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
            }],
            'active' => ['params' => ['active'], 'function' => function ($data) {
                return isset($data['active']) ? $data['active'] : null;
            }]
        ];
    }

    public function getDateAttribute() {
        $date = CommonHelper::timeStampToDate($this->over_date);
        return $this->over_date > time() || !$date ? ($date ? $date : 'Постоянно') : 'В акции не участвует';
    }

    public function getDate($timestamp = null) {
        $date = $timestamp ? $timestamp : $this->over_date;
        $date = $date ? $date : time() + $this->product_life * 24 * 60 * 60;
        $date = CommonHelper::timeStampToDate($date, 'd-n');
        $date = explode('-', $date);
        return (int) $date[0].' '.self::getMonthRus($date[1] - 1);
    }

    public function getLifeDate() {
        return $this->getDate(time() + $this->product_life * 24 * 60 * 60);
    }

    public function isActive() {
        return $this->active && $this->shopSale;
    }

    public static function getMonthRus($monthNumber = 0) {
        $arr = [
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря'
        ];
        return $arr[$monthNumber];
    }

    public function getActiveTextAttribute() {
        return $this->active ? 'Да' : 'Нет';
    }

    public function getAccess()
    {
        return [
            'index' => [1, 2, 3, 4],
            'create' => [1, 2, 3, 4],
            'update' => [1, 2, 3, 4],
        ];
    }

    public function afterSave($insert, $attributes)
    {
        if ($insert && $user = User::authUser()) {
            if ($user->userInfo->user_role_id == 3 || $user->userInfo->user_role_id == 4) {
                $receivers = UserInfo::query()->whereHas('userRole', function ($query) {
                    $query->whereId(2);
                })->get();
                /** @var UserInfo $receiver */
                foreach ($receivers as $receiver) {
                    $receiver->user->notify(new NewProductShopEmail($this->id));
                }
            }
        }
        return parent::afterSave($insert, $attributes); // TODO: Change the autogenerated stub
    }
}
