<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * User endpoints
 *
 * @tags Authentication
 */
class UserController extends Controller
{
    /**
     * Get authenticated user
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "User found successfully!",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "created_at": "2024-04-05T10:00:00Z"
     *   }
     * }
     * @response 404 {
     *   "error": "User not found!"
     * }
     */
    public function user()
    {
        $user = Auth::user();

        if (!isset($user)) {
            return response()->json([
                "error" => "User not found!"
            ], 404);
        }

        $userResource = new UserResource($user);

        return response()->json([
            'success' => true,
            'message' => 'User found successfully!',
            'data' => $userResource
        ], 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if ($user->author) {
            $user->author->update(['name' => $user->name]);
        }

        return Helper::successResponse('User data updated successfully.', new UserResource($user));
    }
}
