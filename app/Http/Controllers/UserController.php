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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
                        'service_id' => $request->role_id == 3 ? 1 : 2,
                        'gender' => 'female',
                        'vehicule_type' => 'taxi',
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

    /**
     * Get user profile data based on user ID and role
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile($userId)
    {
        try {
            $user = User::with('role')->findOrFail($userId);
            $specificData = null;

            switch ($user->role->slug) {
                case 'client':
                    $specificData = Client::where('user_id', $userId)->first();
                    break;
                case 'transporteur':
                    $specificData = Transporteur::where('user_id', $userId)->first();
                    break;
            }

            return response()->json([
                'user' => $user,
                'specific_data' => $specificData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update user's FCM device token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_token' => 'required|string',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $user->device_token = $request->device_token;
            $user->save();

            return response()->json([
                'message' => 'Device token updated successfully',
                'user' => $user,
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update device token',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Send reset code to user's email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            
            // Generate a 4-digit code
            $resetCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Set expiration time (1 hour from now)
            $expiresAt = now()->addHour();
            
            // Update user with reset code
            $user->update([
                'reset_code' => Hash::make($resetCode),
                'reset_code_expires_at' => $expiresAt,
            ]);

            // Send email with reset code
            Mail::raw("Your password reset code is: {$resetCode}\n\nThis code will expire in 1 hour.", function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Password Reset Code');
            });

            return response()->json([
                'message' => 'Reset code has been sent to your email',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to send reset code',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Verify reset code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:4',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user->reset_code || !$user->reset_code_expires_at) {
                Log::error('No reset code found');
                return response()->json([
                    'message' => 'No reset code found',
                    'success' => false
                ], 400);
            }

            if ($user->reset_code_expires_at->isPast()) {
                Log::error('Reset code has expired');
                return response()->json([
                    'message' => 'Reset code has expired',
                    'success' => false
                ], 400);
            }

            if (!Hash::check($request->code, $user->reset_code)) {
                Log::error('Invalid reset code');
                return response()->json([
                    'message' => 'Invalid reset code',
                    'success' => false
                ], 400);
            }

            return response()->json([
                
                'message' => 'Reset code is valid',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to verify reset code',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:4',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user->reset_code || !$user->reset_code_expires_at) {
                return response()->json([
                    'message' => 'No reset code found',
                    'success' => false
                ], 400);
            }

            if ($user->reset_code_expires_at->isPast()) {
                return response()->json([
                    'message' => 'Reset code has expired',
                    'success' => false
                ], 400);
            }

            if (!Hash::check($request->code, $user->reset_code)) {
                return response()->json([
                    'message' => 'Invalid reset code',
                    'success' => false
                ], 400);
            }

            // Update password and clear reset code
            $user->update([
                'password' => Hash::make($request->password),
                'reset_code' => null,
                'reset_code_expires_at' => null,
            ]);

            return response()->json([
                'message' => 'Password has been reset successfully',
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to reset password',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
