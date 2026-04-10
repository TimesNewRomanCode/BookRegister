<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Login endpoint
 *
 * @tags Authentication
 */
class LoginController extends Controller
{
    /**
     * User login
     *
     * Аутентифицирует пользователя и возвращает Sanctum токен.
     *
     * @bodyParam email string required Email пользователя. Example: user@example.com
     * @bodyParam password string required Пароль. Example: password123
     *
     * @response 200 {
     *   "token": "1|abcd...",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "user@example.com"
     *   }
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid credentials"
     * }
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Helper::errorResponse('Invalid credentials');
        }

        $token = $user->createToken('login_token')->plainTextToken;

        $expirationTime = 60 * 24 * 30;
        $user->tokens()->orderBy('created_at', 'desc')->first()->update(['expires_at' => now()->addMinutes($expirationTime)]);
        $user = $user->fresh();

        return response()->json([
            'token' => $token,
            'user' => $user,
        ])->cookie('token', $token, $expirationTime);
    }
}