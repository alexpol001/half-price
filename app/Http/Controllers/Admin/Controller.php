<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends CrudController
{
    public function index(Request $request)
    {
        return redirect('/admin/users/user-info');
    }

    public function login(Request $request)
    {
        return view('admin.auth.login', [
            'title' => 'Логин',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postLogin(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard()->attempt(['email' => $request->get('email'), 'password' => $request->get('password')],$request->filled('remember'))) {
            return redirect()->intended('/admin');
        }
        return back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'Пользователь с таким логином и паролем не найден.']);
    }
}
