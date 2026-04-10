<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Author;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Registration endpoint
 *
 * @tags Authentication
 */
class RegisterController extends Controller
{
    /**
     * User registration
     *
     * Регистрирует нового пользователя и возвращает Sanctum токен.
     *
     * @bodyParam name string required Полное имя. Example: John Doe
     * @bodyParam email string required Email. Example: john@example.com
     * @bodyParam password string required Пароль. Example: password123
     *
     * @response 200 {
     *   "token": "1|abcd...",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com"
     *   }
     * }
     */
    public function register(RegisterRequest $request)
    {
        $role = $request->input('role', 'user');

        if ($role === 'admin') {
            if (User::where('role', 'admin')->exists()) {
                $role = 'user';
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);


        if ($user->role === 'author') {
            $author = Author::create([
                'name' => $user->name,
                'user_id' => $user->id,
            ]);

            $user->author_id = $author->id;
            $user->save();
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $expirationTime = 60 * 24 * 30;

        $user->tokens()->orderBy('created_at', 'desc')->first()->update(['expires_at' => now()->addMinutes($expirationTime)]);
        $user = $user->fresh();

        return response()->json([
            'token' => $token,
            'user' => $user,
        ])->cookie('token', $token, $expirationTime);
    }
}
