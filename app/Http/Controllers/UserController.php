<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Transporteur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'required|digits:8',
        ]);

        try {
            DB::beginTransaction();

            // Create the base user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);
            // Create role-specific user data
            $role = Role::find($request->role_id);
            switch ($role->slug) {
                case 'client':
                    Client::create([
                        'user_id' => $user->id,
                        'email' => $request->email,
                        'username' => $request->name,
                        'password' => $user->password,
                        'phone' => $request->phone,
                    ]);
                    break;

                case 'transporteur':
                    Transporteur::create([
                        'user_id' => $user->id,
                        'email' => $request->email,
                        'username' => $request->name,
                        'password' => $user->password,
                        'phone' => $request->phone,
                    ]);
                    break;
            }

            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user->load('role'),
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user and create token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Load role-specific data based on user's role
        $role = $user->role;
        $specificData = null;

        switch ($role->slug) {
            case 'client':
                $specificData = Client::where('user_id', $user->id)->first();
                break;
            case 'transporteur':
                $specificData = Transporteur::where('user_id', $user->id)->first();
                break;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('role'),
            'specific_data' => $specificData,
            'token' => $token
        ]);
    }
}
