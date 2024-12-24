<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index($email)
    {
        $user = User::where('email', $email)->first();
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    //update google_id
    public function updateGoogleId(Request $request, $id)
    {
        $request->validate([
            'google_id' => 'required|string|max:255',
        ]);
        $user = User::find($id);
        if ($user) {
            $user->google_id = $request->google_id;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => $user,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    }

    // update user
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'address' => 'required',
            'google_id' => 'required|string|max:255',
            'ktp_number' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',

        ]);
        $data = $request->all();
        $user = User::find($id);
        $user->update($data);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    // cek email
    public function checkEmail(Request $request, $email)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);
        $user = User::where('email', $email)->first();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email already registered',
                'valid' => false,
            ], 400);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not registered',
                'valid' => true,

            ], 404);
        }
    }

    // login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }

    // register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',

        ]);

        // $data = $request->all();
        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);
        $role = $request->role;
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], 200);
    }
}
