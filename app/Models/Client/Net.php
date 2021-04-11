<?php

namespace App\Models\Client;

use App\Models\UwtModel;

/**
 * App\Models\Client\Net
 *
 * @property int $id
 * @property string $title
 */
class Net extends UwtModel
{
    public function logo()
    {
        return $this->belongsTo('App\Models\Developer\CropImage', 'logo_crop_image_id', 'id');
    }

    public $sortable = true;

    public function getFields()
    {
        return [
            'logo' => ['slug' => 'logo', 'type' => 'cropImage', 'mimes' => 'jpeg, jpg, png, gif', 'size' => ['width' => 60, 'height' => 60], 'round' => true],
        ];
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
            'title' => 'Название сети',
            'logo' => 'Логотип',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'logo' => 'mimes:jpeg,jpg,png,gif'
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Сети',
                'breadcrumbs' => ['Главная' => static::getController()::getPrefixRoute(), 'Сети' => 'active'],
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Список сетей',
                        'icon' => 'fas fa-cubes',
                        'is_inner_card' => true,
                        'dataModel' => Net::getInstance(),
                        'deletable' => true,
                        'columns' => [
                            ['data' => 'title'],
                        ]
                    ]]
                ],
            ],
            'create' => [
                'title' => 'Сети',
                'subTitle' => 'Добавить сеть',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Сети' => static::getController()::getFullRoute(Net::getInstance()),
                    'Добавить сеть' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Сети',
                'subTitle' => 'Редактировать сеть',
                'breadcrumbs' => [
                    'Главная' => static::getController()::getPrefixRoute(),
                    'Сети' => static::getController()::getFullRoute(Net::getInstance()),
                    'Редактировать сеть' => 'active',
                ],
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
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
