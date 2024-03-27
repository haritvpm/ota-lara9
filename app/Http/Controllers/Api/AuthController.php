<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->roles()->attach(2); // Simple user role

        return response()->json($user);
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $credentials = request(['username', 'password']);
    if (!auth()->attempt($credentials)) {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => [
                    'Invalid credentials'
                ],
            ]
        ], 422);
    }

    $user = User::where('username', $request->username)->first();
    $authToken = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'access_token' => $authToken,
    ]);
}
}
