<?php

namespace App\Http\Controllers\Auth;

use App\Models\Users\Shop;
use App\Models\UwtModel;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/cabinet/users/shop-sale';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

//    /**
//     * Get a validator for an incoming registration request.
//     *
//     * @param  array  $data
//     * @return \Illuminate\Contracts\Validation\Validator
//     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
//        ]);
//    }

//    /**
//     * Create a new user instance after a valid registration.
//     *
//     * @param  array  $data
//     * @return \App\User
//     */
//    protected function create(array $data)
//    {
//        return User::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'password' => Hash::make($data['password']),
//        ]);
//    }

    public function showRegistrationForm() {
        if ($user = User::authUser()) {
            if ($user->userInfo->userRole->id != 3 && $user->userInfo->userRole->id != 4) {
                Auth::logout();
            } else {
                return \redirect('/');
            }
        }
        return view('web.pages.signup');
    }

    public function register(Request $request) {
        $model = Shop::getInstance();
        $requestData = $this->doValidate($request, $model);
        $model->store($requestData);
        Auth::guard()->attempt(['email' => $request->get('email'), 'password' => $request->get('password')]);
        return redirect()->intended($this->redirectPath());
    }

    /**
     * @param Request $request
     * @param UwtModel $model
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function doValidate(Request $request, UwtModel $model)
    {
        foreach ($model->generateAttributes() as $key => $field) {
            if (!$request->get($key)) {
                $data = [];
                if (isset($field['params'])) {
                    foreach ($field['params'] as $param) {
                        $data[$param] = $request->get($param);
                    }
                }
                if (isset($field['isOnlyCreate']) && $field['isOnlyCreate']) {
                    if (!$model->id) {
                        $request->merge([$key => $field['function']($data)]);
                    }
                } else {
                    $request->merge([$key => $field['function']($data)]);
                }
            }
        }
        parent::validate($request, $model->rules(), $model->errorMessages(), $model->getLabels());
        return $request->all();
    }
}
