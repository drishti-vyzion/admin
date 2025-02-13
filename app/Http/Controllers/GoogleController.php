<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect('/auth/google/callback');
    }
    public function callback()
    {  
        try {
            $googleUser = Socialite::driver('google')->user();
            //  dd($googleUser);
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
        }
        $existingUser = User::where('email', $googleUser->email)->first();
        if ($existingUser) {
            $existingUser->update([
                'google_id' => $googleUser->getId(),    
                'avatar' => $googleUser->getAvatar(),
                $existingUser->name = $googleUser->getName(),   
                $existingUser->email_verified_at = now(),        
                $existingUser->save(), 
            ]);
            Auth::login($existingUser);
        } else {
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->id, 
                'avatar' => $googleUser->avatar, 
                'password' => bcrypt(Str::random(16)), 
                'email_verified_at' => now(), 
            ]);
            Auth::login($newUser);
        }
        return redirect()->route('dashboard');
    }
}
