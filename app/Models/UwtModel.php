<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use App\Http\Controllers\CrudController;
use App\Models\Developer\CropImage;
use App\Models\Developer\Model;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

/**
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereTitle($value)
 * @method static UwtModel find($id)
 * @method static UwtModel create($data)
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int $sort
 * @property string $resource_url
 */
abstract class UwtModel extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var CrudController $controller
     */
    protected static $controller;

    public $timestamps = false;

    protected $slugSource = false;

    public $sortable = false;

    public function getFields()
    {
        return [];
    }

    public function hasRule($fieldSlug, $ruleName)
    {
        $rules = static::rules();
        if (isset($rules[$fieldSlug]) && $rule = explode('|', $rules[$fieldSlug])) {
            return in_array($ruleName, $rule);
        }
        return false;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function getLabels()
    {
        return [];
    }

    public function getPlaceHolders()
    {
        return [];
    }

    public function getLabel($key)
    {
        $labels = $this->getLabels();
        return isset($labels[$key]) ? $labels[$key] : ucfirst($key);
    }

    public function getValue($key)
    {
        $relations = explode('_', $key);
        $value = $this;
        for ($i = 0; $i < count($relations); $i++) {
            $attr = $relations[$i];
            if (isset($value->$attr)) {
                $value = $value->$attr;
            } elseif (isset($this->$key)) {
                $value = $this->$key;
            } else {
                return null;
            }
        }
        return $value;
    }

    public function getPlaceHolder($key)
    {
        $placeHolders = $this->getPlaceHolders();
        return isset($placeHolders[$key]) ? $placeHolders[$key] : $this->getLabel($key);
    }


    /**
     * @param array $attributes
     * @return UwtModel|bool
     */
    public function store(array $attributes = [])
    {

        $model = new static($attributes);
        if ($model->beforeSave(true, $attributes)) {
            if ($model->save()) {
                if ($model->afterSave(true, $attributes)) {
                    return $model;
                }
            }
        }
        return false;
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return $this|bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $this->fill($attributes);
        if ($this->beforeSave(false, $attributes)) {
            if ($this->save()) {
                if ($this->afterSave(false, $attributes)) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function delete()
    {
        if ($this->beforeDelete()) {
            if (parent::delete()) {
                return $this->afterDelete();
            }
        }
        return false;
    }

    public function errorMessages()
    {
        return [
            'required' => 'Необходимо заполнить «:attribute».',
            'email' => 'Значение «:attribute» не является правильным email адресом.',
            'unique' => 'Значение поля «:attribute» должно быть уникальным.',
            'same' => '«:attribute» и «:other» должны совпадать.',
            'integer' => 'Значение поля «:attribute» должно быть целым числом.',
            'max' => 'Значение поля «:attribute» не должно быть меньше :max.',
            'min' => 'Значение поля «:attribute» не должно быть больше :min.',
            'numeric' => 'Значение поля «:attribute» должно быть числом.',
            'mimes' => 'Файл «:attribute» должен иметь тип: :values.',
            'string' => 'Значение поля «:attribute» должно быть строкой.',
            'regex' => 'Поле «:attribute» имеет неверный формат.',
            'exists' => '«:attribute» не существует.',
        ];
    }

    /**
     * @param null|string $componentSlug
     * @return array
     */
    public function getComponent($componentSlug = null)
    {
        return [];
    }

    public function generateAttributes()
    {
        return [
//            'slug' => ['params' => [$this->slugSource], 'function' => function ($data) {
//                return strtolower(CommonHelper::translate($data[$this->slugSource]));
//            }]
            'sort' => ['isOnlyCreate' => true, 'function' => function ($data) {
                $sort = $this->sortable ? static::query()->max('sort') : null;
                return ($sort || $sort == 0) ? $sort + 1 : 0;
            }]
        ];
    }

    public static function getRoute()
    {
        static $route = null;
        if ($route === null) {
            $path = str_replace('App\\Models\\', '', static::class);
            $path = str_replace('\\', '/', $path);
            $path = preg_replace('/\B([A-Z])/', '-$1', $path);
            $path = mb_strtolower($path);
            $route = '/' . $path;
        }
        return $route;
    }

    /**
     * @return UwtModel
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $class = static::class;
            $instance = new $class();
        }
        return $instance;
    }

    public function getDataBaseModel() {
        static $instance = null;
        if ($instance === null) {
            $instance = static::find($this->id);
        }
        return $instance;
    }

    public function getSlugSource()
    {
        return $this->slugSource;
    }

    public function exclude()
    {
        $result = [];
        $models = $this->getTree();
        foreach ($models as $model) {
            $result[] = $model->id;
        }
        return $result;
    }

    /**
     * @param bool $excludeSelf
     * @return UwtModel[]
     */
    public function getTree($excludeSelf = false)
    {
        $class = explode('\\', get_class($this));
        $relationName = lcfirst(array_pop($class)) . 's';
        $models = [];
        if (!$excludeSelf) {
            $models = [$this];
        }
        /** @var UwtModel $model */
        foreach ($this->$relationName as $model) {
            $models = array_merge($models, $model->getTree());
        }
        return $models;
    }

    /**
     * @param $params
     * @return array
     */
    public function getPages($params)
    {
        return [];
    }

    /**
     * @param $page
     * @param array $params
     * @return mixed
     */
    public function getPage($page, $params = [])
    {
        $access = $this->getAccess();
        if (isset($access[$page]) && $access = $this->getAccess()[$page]) {
            if (!(User::authUser() && in_array(User::authUser()->userInfo->userRole->id, $access))) {
                return abort(404);
            }
        }
        $pages = $this->getPages($params);
        return isset($pages[$page]) ? $pages[$page] : abort(404);
    }

    public static function setController($controller)
    {
        static::$controller = $controller;
    }

    /**
     * @return CrudController
     */
    public static function getController()
    {
        return static::$controller;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            if (static::getInstance()->sortable) {
                $builder->orderBy('sort');
            }
        });
    }

    public function getResourceUrlAttribute()
    {
        $url = strtolower(str_replace('App\Models', '', static::class));
        return '/upload/model' . str_replace('\\', '/', $url) . '/' . $this->id;
    }

    /**
     * @param $insert
     * @param $attributes
     * @return bool
     */
    public function beforeSave($insert, $attributes)
    {
        return $attributes;
    }

    public function afterSave($insert, $attributes)
    {
        foreach ($this->getFields() as $field) {
            $slug = $field['slug'];
            switch ($field['type']) {
                case 'cropImage':
                    CropImage::saveCropImage($this, $slug, $attributes);
                    break;
            }
        }
        return true;
    }

    public function getAccess() {
        return [];
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        CommonHelper::rmRec(public_path($this->resource_url));
        return true;
    }

    public function afterDelete()
    {
        try {
            foreach ($this->getFields() as $field) {
                $slug = $field['slug'];
                switch ($field['type']) {
                    case 'cropImage':
                        CropImage::deleteCropImage($this, $slug);
                        break;
                }
            }
        } catch (\Exception $e) {}
        return true;
    }
}
