<?php

namespace App\Models;

use App\Mail\NewProductEmail;
use App\Models\Products\ProductCategory;
use App\Models\Products\ProductMeasure;
use App\Models\Users\UserInfo;
use http\Client\Curl\User;
use Illuminate\Support\Facades\Mail;

/**
 * App\Models\Products\Product
 *
 * @property int $id
 * @property int $product_measure_id
 * @property int $product_category_id
 * @property string $title
 * @property string $description
 * @property double $measure_value
 * @property string $code
 * @property int $active
 * @property double $price
 */
class Product extends UwtModel
{
    public function logo()
    {
        return $this->belongsTo('App\Models\Developer\CropImage', 'logo_crop_image_id', 'id');
    }

    public function measure()
    {
        return $this->belongsTo('App\Models\Products\ProductMeasure', 'product_measure_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Products\ProductCategory', 'product_category_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Users\ShopProduct', 'product_id', 'id');
    }

    public function getFields()
    {
        return [
            'logo' => ['slug' => 'logo', 'type' => 'cropImage', 'mimes' => 'jpeg, jpg, png, gif', 'size' => ['width' => 240, 'height' => 240]],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_category_id', 'product_measure_id', 'title', 'description', 'measure_value', 'code', 'active'
    ];

    public function rules()
    {
        return [
            'product_category_id' => 'required|exists:product_categories,id',
            'product_measure_id' => 'required|exists:product_measures,id',
            'title' => 'required|string|max:255',
            'logo' => 'mimes:jpeg,jpg,png,gif',
            'description' => 'string|max:255|nullable',
            'measure_value' => 'required|numeric',
            'code' => 'required|numeric|unique:products,code,' . $this->id,
            'active' => 'nullable'
        ];
    }

    public function getLabels()
    {
        return [
            'product_category_id' => 'Категория',
            'product_measure_id' => 'Единица измерения',
            'title' => 'Название продукта',
            'description' => 'Описание продукта',
            'logo' => 'Изображение продукта',
            'measure_value' => 'Вес/объем/кол-во',
            'code' => 'Штрих-код',
            'active' => 'Прошел проверку',
        ];
    }

    public function getPages($params) {
        return [
            'index' => [
                'title' => 'Продукты',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Продукты' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Продукты',
                        'icon' => 'fas fa-shopping-basket',
                        'is_inner_card' => true,
                        'dataModel' => Product::getInstance(),
                        'deletable' => true,
                        'columns' => [
                            ['data' => 'title'],
                            ['data' => 'product_category_id', 'name' => 'category.title'],
                            ['data' => 'code'],
                            ['data' => 'active', 'name' => 'mutator.active_text']
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Продукты',
                'subTitle' => 'Добавить продукт',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Продукты' => static::getController()::getFullRoute(Product::getInstance()),
                    'Добавить продукт' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.select2' => ['slug' => 'product_category_id', 'dataModel' => ProductCategory::getInstance()]],
                                ['interface.fields.simple' => ['slug' => 'code']],
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
                                ['interface.fields.textarea' => ['slug' => 'description']],
                                ['interface.build.row' => [
                                    'components' => [
                                        ['interface.build.div' => [
                                            'class' => 'col-sm-6',
                                            'components' => [
                                                ['interface.fields.simple' => ['slug' => 'measure_value']],
                                            ]
                                        ]],
                                        ['interface.build.div' => [
                                            'class' => 'col-sm-6',
                                            'components' => [
                                                ['interface.fields.select2' => ['slug' => 'product_measure_id', 'dataModel' => ProductMeasure::getInstance(), 'selected' => true]],
                                            ]
                                        ]],
                                    ]
                                ]],
                                ['interface.fields.check' => ['slug' => 'active']]
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Продукты',
                'subTitle' => 'Редактировать продукт',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Продукты' => static::getController()::getFullRoute(Product::getInstance()),
                    'Редактировать продукт' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.select2' => ['slug' => 'product_category_id', 'dataModel' => ProductCategory::getInstance()]],
                                ['interface.fields.simple' => ['slug' => 'code']],
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
                                ['interface.fields.textarea' => ['slug' => 'description']],
                                ['interface.build.row' => [
                                    'components' => [
                                        ['interface.build.div' => [
                                            'class' => 'col-sm-6',
                                            'components' => [
                                                ['interface.fields.simple' => ['slug' => 'measure_value']],
                                            ]
                                        ]],
                                        ['interface.build.div' => [
                                            'class' => 'col-sm-6',
                                            'components' => [
                                                ['interface.fields.select2' => ['slug' => 'product_measure_id', 'dataModel' => ProductMeasure::getInstance()]],
                                            ]
                                        ]],
                                    ]
                                ]],
                                ['interface.fields.check' => ['slug' => 'active']]
                            ]]
                        ]]
                    ]]
                ]
            ]
        ];
    }

    public function getActiveTextAttribute() {
        return $this->active ? 'Да' : 'Нет';
    }

    public function getAccess()
    {
        return [
            'index' => [1, 2, 3, 4],
            'create' => [1, 2, 3, 4],
            'update' => [1, 2],
        ];
    }

    public function generateAttributes()
    {
        return [
            'active' => ['params' => ['active'], 'function' => function ($data) {
                return isset($data['active']) ? $data['active'] : null;
            }]
        ];
    }

    public function afterSave($insert, $attributes)
    {
        if ($insert && $user = \App\User::authUser()) {
            if ($user->userInfo->user_role_id == 3 || $user->userInfo->user_role_id == 4) {
                $receivers = UserInfo::query()->whereHas('userRole', function ($query) {
                    $query->whereId(2);
                })->get();
                /** @var UserInfo $receiver */
                foreach ($receivers as $receiver) {
                    $receiver->user->notify(new NewProductEmail($this->id));
                }
            }
        }
//        MAIL_DRIVER=smtp
//MAIL_HOST=smtp.yandex.ru
//MAIL_PORT=465
//MAIL_USERNAME=tiaeln@gmail.com
//MAIL_PASSWORD=951753
//MAIL_ENCRYPTION=ssl
        return parent::afterSave($insert, $attributes); // TODO: Change the autogenerated stub
    }
}
