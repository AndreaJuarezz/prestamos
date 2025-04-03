<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class RegisterController extends Controller{

    // Muestra el formulario de registro
    public function showRegistrationForm(){
        return view('auth.register');
    }

public function register(Request $request)
{
    $validator = Validator::make($request -> all(),
    [
        'name' =>'required|string|max:255',
        'email' =>'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Si los datos no son validos, redirige de vuelta al formulario de registro
    if ($validator->fails()) {
        return redirect()->route('register')
            ->withErrors($validator)
            ->withInput();
    }

    // Crea el usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // iniciar sesion al usuario
    auth()->login($user);

    return redirect('/');
}
}

