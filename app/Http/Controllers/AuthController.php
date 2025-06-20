<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(){
        $fields = $request->validate([
            "username" => "required|max:255|unique:users",
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "contact" => "required|max:255|unique:users",
            "whatsapp_number" => "required|max:255|unique:users",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        $user = User::create([
            ...$fields,
            'status' => 'active',
            'role' => 'user',
        ]);

        $token = $user->createToken($user->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(){
        return 'login';
    }

    public function logout(){
        return 'logout';
    }
}
