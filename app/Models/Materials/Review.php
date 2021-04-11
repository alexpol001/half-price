<?php

namespace App\Models\Materials;

use App\Models\Developer\CropImage;
use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 * @property string $city
 * @property string $description
 * @property int $stars
 * @property CropImage $logo $logo
 */
class Review extends UwtModel
{
    public $sortable = true;

    public function logo()
    {
        return $this->belongsTo('App\Models\Developer\CropImage', 'logo_crop_image_id', 'id');
    }

    public function getFields()
    {
        return [
            'logo' => ['slug' => 'logo', 'type' => 'cropImage', 'mimes' => 'jpeg, jpg, png, gif', 'size' => ['width' => 277, 'height' => 351]],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'city', 'sort', 'stars'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Заголовок',
            'description' => 'текст',
            'city' => 'Город',
            'stars' => 'Оценка',
            'logo' => 'Изображение',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'required|string|max:255',
            'stars' => 'required|integer|min:0|max:5',
            'logo' => 'mimes:jpeg,jpg,png,gif'
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Отзывы',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Отзывы',
                        'is_inner_card' => true,
                        'dataModel' => Review::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'Отзывы',
                'subTitle' => 'Добавить',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.textarea' => ['slug' => 'description']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
                                ['interface.fields.simple' => ['slug' => 'city']],
                                ['interface.fields.simple' => ['slug' => 'stars']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Отзывы',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.textarea' => ['slug' => 'description']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
                                ['interface.fields.simple' => ['slug' => 'city']],
                                ['interface.fields.simple' => ['slug' => 'stars']],
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
