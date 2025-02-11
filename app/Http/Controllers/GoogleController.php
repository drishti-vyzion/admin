<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
 
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {dd(123);
        $googleAuthUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
           
        return response()->json([
            'url' => $googleAuthUrl
        ]);
    }
    public function handleGoogleCallback()
    {
            try {
                // Get the user information from Google
                $user = Socialite::driver('google')->user();
            } catch (Throwable $e) {
                return response()->json([
                    'error' => 'Google authentication failed.',
                    'message' => $e->getMessage(),
                ], 401);
        
            }
    
            // Check if the user already exists in the database
            $existingUser = User::where('email', $user->email)->first();
    
            if ($existingUser) {
                // Log the user in if they already exist
                Auth::login($existingUser);
            } else {
                // Otherwise, create a new user and log them in
                $newUser = User::updateOrCreate([
                    'email' => $user->email,
                    'name' => $user->name,
                    'password' => bcrypt(Str::random(16)), // Set a random password
                    'email_verified_at' => now()
                ]);
                Auth::login($newUser);
            }
    
            // Redirect the user to the dashboard or any other secure page
            return response()->json([
                'message' => 'Authentication successful',
                'user' => Auth::user(),
                'token' => Auth::user()->createToken('API Token')->plainTextToken, // If using Laravel Sanctum for API authentication
            ]);

        }
    
    }

