<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request){
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
            'role' => 'student',
        ]);

        $token = $user->createToken($user->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request){
        $fields = $request->validate([
            "email" => "required|email|exists:users",
            "password" => "required"
        ]);
        
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)){
            return [
                "message" => "Invalid Credentials"
            ];
        }
        
        $token = $user->createToken($user->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(){
        $request->user()->tokens()->delete();

        return [
            "message" => "You are logged out"
        ];
    }
}
