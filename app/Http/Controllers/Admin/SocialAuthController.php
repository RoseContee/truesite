<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function authGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function authGoogleCallback(Request $request) {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleUser = json_decode(json_encode($googleUser), true);
            $admin = Admin::where('email', $googleUser['email'])->first();
            if($admin) {
                if (empty($admin['google_id'])) {
                    $admin['google_id'] = $googleUser['id'];
                    $admin->save();
                }
                auth('admin')->loginUsingId($admin['id']);
                return redirect()->route('admin');
            }
            $message = 'Incorrect Email.';
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            $message = 'Cannot use google function at this time';
            if ($request['error_description']) $message = $request['error_description'];
        }
        return redirect()->route('admin.login')->with('error_message', $message);
    }
}
