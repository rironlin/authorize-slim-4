<?php

namespace App\Http\Controllers;

use Auth;
use App\Support\View;
use App\Support\RequestInput;
use Illuminate\Support\Arr;

class LoginController
{
    public function show(View $view)
    {
        return $view('auth.login');
    }

    public function store(RequestInput $input)
    {
        $form = $input->all();

        $form['will_fail'] = null;
        $rules = [
            'email' => 'required|email',
            'will_fail' => 'required',
            'password' => 'required|string'
        ];

        $validator = validator(
            $form,
            $rules,
        );

        if ($validator->fails()) {
            $messages = Arr::collapse(
                array_values($validator->errors()->getMessages())
            );

            session()->flash()->set('errors', $messages);

            return back();
        }

        $successful = Auth::attempt($input->email, sha1($input->password));

        if (!$successful) {
            dd("Unsuccessful Login Attempt");
        }

        return redirect('/home');
    }

    public function logout()
    {
        Auth::logout();

        if (Auth::guest()) {
            return redirect('/login');
        }
    }
}
