<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $deviceInfo = $request->header('User-Agent', 'Unknown Device');
        $tokenName = $user->username . ' - ' . substr($deviceInfo, 0, 50);

        $token = $user->createToken($tokenName);

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
        
        // Get device info (optional)
        $deviceInfo = $request->header('User-Agent', 'Unknown Device');
        $tokenName = $user->username . ' - ' . substr($deviceInfo, 0, 50);
        
        // Delete old tokens if user has more than 1 (keeping max 2 total)
        $existingTokens = $user->tokens()->count();
        if ($existingTokens >= 2) {
            // Delete the oldest token
            $oldestToken = $user->tokens()->orderBy('created_at', 'asc')->first();
            if ($oldestToken) {
                $oldestToken->delete();
            }
        }
        
        $token = $user->createToken($tokenName);

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
            'active_sessions' => $user->tokens()->count()
        ];
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return [
            "message" => "You are logged out"
        ];
    }

    public function sessions(Request $request){
        $tokens = $request->user()->tokens()->select('id', 'name', 'created_at', 'last_used_at')->get();
        
        return [
            'active_sessions' => $tokens,
            'total_sessions' => $tokens->count()
        ];
    }

    public function revokeSession(Request $request, $tokenId){
        $token = $request->user()->tokens()->find($tokenId);
        
        if (!$token) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        
        $token->delete();
        
        return [
            'message' => 'Session revoked successfully'
        ];
    }
}
