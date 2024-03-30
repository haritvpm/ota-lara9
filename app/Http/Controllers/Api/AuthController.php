<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    // 
    //SAnctum which was not working due to csrf mismatch. public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|min:6'
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //     ]);

    //     $user->roles()->attach(2); // Simple user role

    //     return response()->json($user);
    // }

    // public function login(Request $request)
    // {
    // $request->validate([
    //     'username' => 'required',
    //     'password' => 'required'
    // ]);

    // $credentials = request(['username', 'password']);
    // if (!auth()->attempt($credentials)) {
    //     return response()->json([
    //         'message' => 'The given data was invalid.',
    //         'errors' => [
    //             'password' => [
    //                 'Invalid credentials'
    //             ],
    //         ]
    //     ], 422);
    // }

    // $user = User::where('username', $request->username)->first();
    // $authToken = $user->createToken('auth-token')->plainTextToken;

    // return response()->json([
    //     'access_token' => $authToken,
    // ]);
    // }

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','refresh','logout']]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user =  Auth::guard('api')->user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
                'refresh_token' => '',
                'type' => 'bearer',
                 

            ]);

            // return response()->json([
            //     'status' => 'success',
            //     'user' => $user,
            //     'authorisation' => [
            //         'access_token' => $token,
            //         'type' => 'bearer',
            //        // 'expires_in' => auth()->factory()->getTTL() * 60

            //     ]
            // ]);

    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::guard('api')->user(),
            'authorisation' => [
                'token' => Auth::guard('api')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me(Request $request)
    {
        $user = Auth::user();

      return response()->json([
        'id' =>  $user->id,
        'name' =>$user->name,
        'username' =>$user->username,
        'email' =>$user->email,
        'avatar' =>'',
        'roles' => '',
        'permissions' => '',


    ]);

    }
}
