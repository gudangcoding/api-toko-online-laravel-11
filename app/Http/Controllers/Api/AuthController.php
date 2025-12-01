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
        $success = $user;
        $success['token'] = $user->createToken('Tokoku', ['user'])->plainTextToken;
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
            $success = $user;
            $token = $user->createToken('MyApp', ['user'])->plainTextToken;

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

    // Update profil pengguna
    public function updateProfile(Request $request, $id)
    {
        // Hanya boleh update profil sendiri
        $authUser = $request->user();
        if (!$authUser || (string)$authUser->id !== (string)$id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this profile',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'province_id' => 'nullable|string',
            'city_id' => 'nullable|string',
            'district_id' => 'nullable|string',
            'foto' => 'nullable|string', // URL atau path gambar
            'password' => 'nullable|string|min:6',
            'c_password' => 'nullable|string|same:password',
        ]);

        $user = User::findOrFail($id);
        // Hindari menulis password kosong: exclude password/c_password dari fill
        $updateData = collect($validated)->except(['password', 'c_password'])->all();
        $user->fill($updateData);
        // Hanya update password bila diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }
}
