<?php

// SocialAuthFacebookController.php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

class SocialAuthFacebookController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return mixed
     */
    public function redirect(Request $request)
    {
        return Socialite::driver ('facebook')
                        ->stateless()
                        ->with(['state' => $request->return_url])
                        ->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback(Request $request, Response $response)
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();

        $user = User::firstOrCreate(
            [
              'facebook_id' => $facebookUser->getId()
            ],
            [
              'facebook_id' => $facebookUser->getId(),
              'facebook_token' => $facebookUser->token,
              'name' => $facebookUser->name,
              'email' => $facebookUser->email,
              'photo' => str_replace('normal', 'large', $facebookUser->getAvatar())
            ]
        );

        return redirect(
          '//localhost:3000/login' .
          '/' . JWTAuth::fromUser($user)) .
          $request->state;
    }

    /**
     * Log out
     *
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();
        // JWTAuth::logout(true);

        return 'ok';
    }
}
