<?php

namespace App;

use App\Mail\ResetPasswordEmail;
use App\Models\Users\UserInfo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/**
 * Class User
 *
 * @method static User find($id)
 * @method static User create($data)
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property int $user_info_id
 * @property UserInfo $userInfo
 */
class User extends Authenticatable
{
    public function userInfo() {
        return $this->belongsTo('App\Models\Users\UserInfo');
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        self::created(function($model){
            /** @var User $model */
            if (!$model->userInfo) {
                $info = $model->userInfo()->create(
                    ['user_role_id' => 4]
                );
                $model->user_info_id = $info->id;
                $model->save();
            }
        });
    }

    /**
     * @return User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function authUser() {
        $authUser = Auth::user();
        return $authUser ? $authUser : null;
    }

    public function hasRule($fieldSlug, $ruleName)
    {
        $rules = static::rules();
        if (isset($rules[$fieldSlug]) && $rule = explode('|', $rules[$fieldSlug])) {
            return in_array($ruleName, $rule);
        }
        return false;
    }

    public function getLabel($key)
    {
        $labels = $this->getLabels();
        return isset($labels[$key]) ? $labels[$key] : ucfirst($key);
    }

    public function getLabels()
    {
        return [
            'email' =>'Ваш email',
            'password' => 'Пароль',
            'remember' => 'Запомнить меня',
            'password_confirmation' => 'Повторите пароль'
        ];
    }

    public function getPlaceHolders()
    {
        return [];
    }

    public function getPlaceHolder($key)
    {
        $placeHolders = $this->getPlaceHolders();
        return isset($placeHolders[$key]) ? $placeHolders[$key] : $this->getLabel($key);
    }

    public function rules()
    {
        $rules = [
            'email' => 'required|string|max:255',
            'password' => 'string|min:8|required',
        ];
        return $rules;
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmail($token));
    }
}
