<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Logout endpoint
 *
 * @tags Authentication
 */
class LogoutController extends Controller
{
    /**
     * User logout
     *
     * @authenticated
     *
     * Удаляет текущий токен авторизации пользователя.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logged out successfully!"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "You are unauthenticated!"
     * }
     */
    public function logout(Request $request)

    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        return Helper::successResponse("Logged out successfully!");
    }
}
