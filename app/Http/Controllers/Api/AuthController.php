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
        $this->middleware('auth:api', ['except' => ['login', 'register','logout']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');

        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user =  auth('api')->user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'refresh_token' =>$token,
            'type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600

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

    public function register(Request $request)
    {
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
            'access_token' => $token,
            'refresh_token' => $token,
             'type' => 'bearer',
            
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
        \Log::info('in refres');
       if(! auth()->guard('api')->check()){
        return response()->json([
            'status' => 'failed',
          
            
        ]);
       }


       $token =  auth('api')->refresh();
        return response()->json([
            'status' => 'success',
            //'user' => auth('api')->user(),
            'access_token' => $token,
            'refresh_token' => $token,
            'type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600

            
        ]);
    }
    

    public function me(Request $request)
    {
        $user = User::with(['roles', 'roles.permissions'])->find(Auth::id());

        $permList = collect();
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $p) {
                $permList->add($p->title);
            }
        }

        return response()->json([
            'id' =>  $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => '',
            'roles' =>  $user->roles->pluck('title'),
            'permissions' => $permList->unique()  ,
        ]);
    }
}
