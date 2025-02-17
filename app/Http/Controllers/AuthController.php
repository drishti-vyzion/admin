<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemListResource;
use App\Http\Resources\UserResource;
use App\Mail\PasswordChangedMail;
use App\Mail\RegistrationSuccessMail;
use App\Models\Item;
use App\Models\like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|string',
        ]);
        $User = User::where('email', $request->email)->first();
        if($User){
            return response()->json(['already register']); 
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('authToken')->plainTextToken;
        //  dd( Mail::to($user->email));
    //    Mail::to($user->email)->send(new RegistrationSuccessMail($user));
 
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user)
        ], 201);
    
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            // 'new_password' => 'required|min:8|confirmed',
        ]);
        $user = User::where('email', $request->email)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        //     dd(!Hash::check($request->current_password, $user->password)); 
        if (strcmp($request->get('previous_password'), $request->new_password) == 0) {
            return response()->json(['password' => 'same Password'], 400);
        }
        $user->update(['password' => Hash::make($request->new_password)]);
       // Mail::to($user->email)->send(new PasswordChangedMail($user));
        return response()->json(['message' => 'Password reset successful, email sent']);
    }


    public function index(Request $request)
    {
        //
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user()->tokens();

        if(!$user){
            return response()->json([ 'already logout' ]);
        }
        $user->delete();
        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }
   
}
