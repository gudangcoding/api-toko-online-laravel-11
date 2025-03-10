<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest; // Custom request untuk validasi register
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Fungsi untuk register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success =  $user;
        $success['token'] =  $user->createToken('Tokoku', ['user'])->plainTextToken;
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Register success!',
                'data' => $success,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Gagal!'
            ], 401);
        }
    }

    // Fungsi untuk login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = User::select('*')->find(auth()->guard()->user()->id);
            $success =  $user;
            $token =  $user->createToken('MyApp', ['user'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login success!',
                'user' => $user,
                'token' => $token
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ], 401);
        }
    }

    // Fungsi untuk logout
    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    // Fungsi untuk mendapatkan profil pengguna
    public function profile(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found!',
            ], 404);
        }
        return response()->json([
            'user' => $user,
        ], 200);
    }
}
