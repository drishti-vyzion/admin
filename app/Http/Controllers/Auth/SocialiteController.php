<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        $user = User::firstOrCreate(
            ['email' => $facebookUser->getEmail()],
            [
                'name' => $facebookUser->getName(),
                'facebook_id' => $facebookUser->getId(),
                'avatar' => $facebookUser->getAvatar(),
            ]
        );
        Auth::login($user);
        return new UserResource($user);
    }
    public function getFacebookFriends()
    {
        $user = Socialite::driver('facebook')->user();
        $access_token = $user->token;
        $client = new Cient();
        $response = $client->get('https://graph.facebook.com/v2.11/me/friends', [
            'query' => [
                'access_token' => $access_token,  // Use the actual access token here
            ]
        ]);
        $body = $response->getBody();
        $result = \GuzzleHttp\json_decode($body, true);
        return view('pages.admin.posts.create', ['result' => $result]);
    }
}
