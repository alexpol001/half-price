<?php

namespace App\Models\Materials\About;

use App\Models\Developer\CropImage;
use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 * @property CropImage $logo $logo
 */
class Advantage extends UwtModel
{
    public $sortable = true;

    public function logo()
    {
        return $this->belongsTo('App\Models\Developer\CropImage', 'logo_crop_image_id', 'id');
    }

    public function getFields()
    {
        return [
            'logo' => ['slug' => 'logo', 'type' => 'cropImage', 'mimes' => 'jpeg, jpg, png, gif', 'size' => ['width' => 170, 'height' => 170]],
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
            'title' => 'Заголовок',
            'logo' => 'Изображение',
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
                'title' => 'О нас / Преимущества',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'О нас / Преимущества',
                        'is_inner_card' => true,
                        'dataModel' => Advantage::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'О нас / Преимущества',
                'subTitle' => 'Редактировать',
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
                'title' => 'О нас / Преимущества',
                'subTitle' => 'Добавить',
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
