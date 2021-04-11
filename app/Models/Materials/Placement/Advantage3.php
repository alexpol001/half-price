<?php

namespace App\Models\Materials\Placement;

use App\Models\Developer\CropImage;
use App\Models\UwtModel;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property CropImage $logo $logo
 */
class Advantage3 extends UwtModel
{
    public $sortable = true;

    public function logo()
    {
        return $this->belongsTo('App\Models\Developer\CropImage', 'logo_crop_image_id', 'id');
    }

    public function getFields()
    {
        return [
            'logo' => ['slug' => 'logo', 'type' => 'cropImage', 'mimes' => 'jpeg, jpg, png, gif', 'size' => ['width' => 120, 'height' => 120], 'round' => true],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'sort'
    ];


    public function getLabels()
    {
        return [
            'title' => 'Заголовок',
            'description' => 'Описание',
            'logo' => 'Изображение',
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'logo' => 'mimes:jpeg,jpg,png,gif'
        ];
    }

    public function getPages($params)
    {
        return [
            'index' => [
                'title' => 'Размещение / Преимущества',
                'components' => [
                    ['interface.data-table' => [
                        'title' => 'Размещение / Преимущества',
                        'is_inner_card' => true,
                        'dataModel' => Advantage3::getInstance(),
                        'deletable' => true,
                        'sortable' => true,
                        'columns' => [
                            ['data' => 'title']
                        ]
                    ]]
                ]
            ],
            'create' => [
                'title' => 'Размещение / Преимущества',
                'subTitle' => 'Добавить',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'description']],
                                ['interface.fields.file.image' => $this->getFields()['logo']],
                            ]]
                        ]]
                    ]]
                ]
            ],
            'update' => [
                'title' => 'Размещение / Преимущества',
                'subTitle' => 'Редактировать',
                'components' => [
                    ['interface.form' => [
                        'files' => true,
                        'components' => [['interface.card' => [
                            'components' => [
                                ['interface.fields.simple' => ['slug' => 'title']],
                                ['interface.fields.simple' => ['slug' => 'description']],
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
