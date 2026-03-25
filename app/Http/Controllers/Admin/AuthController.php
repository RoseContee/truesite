<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login() {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request) {
        $rule = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (auth('admin')->attempt($request->only(['email', 'password']), $request['remember'] == 1)) {
            return redirect()->route('admin');
        }
        return back()->withInput()->with('error_message', 'Credentials does not match.');
    }

    public function forgot() {
        return view('admin.auth.forgot-password');
    }

    public function postForgot(Request $request) {
        $rule = [
            'email' => ['required', 'email', 'exists:admins'],
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $email = $request['email'];
        $token = Str::random(32);
        $now = now();
        $password_reset = PasswordReset::firstOrCreate([
            'type' => 'admin',
            'email' => $email,
        ], [
            'token' => $token,
            'created_at' => $now,
        ]);
        if (!$password_reset['token'] || $now->diffInSeconds(new Carbon($password_reset['created_at'])) > 15 * 60) {
            $password_reset['token'] = $token;
            $password_reset['created_at'] = $now;
            $password_reset->save();
        }
        /*try {
            $mail = MailTemplate::ofCategory('forgot-password')->first();
            $subject = str_replace('{Email}', $email, $mail['subject']);
            $mail_body = str_replace('{Email}', $email, $mail['body']);
            $mail_body = str_replace('{Link}', route('admin.reset-password', $password_reset['token']), $mail_body);
            $data = [
                'subject' => $subject,
                'mail_body' => $mail_body,
            ];
            Mail::to($email)->send(new Template($data));
        } catch(\Exception $exception) {
            return back()->withInput()->with('error_message', 'Sorry! Some went error. Please try again.');
        }*/
        return back()->with('info_message', 'We sent rest link to your email. If you did not receive, please try again.');
    }

    public function reset($token) {
        $password_reset = PasswordReset::type('admin')->where('token', $token)->first();
        if (!$password_reset) {
            return view('admin.auth.not-found', [
                'error_message' => 'Your reset request is invalid. Please check your inbox again.',
            ]);
        }
        if (now()->diffInSeconds(new Carbon($password_reset['created_at'])) > 15 * 60) {
            $password_reset['token'] = null;
            $password_reset->save();
            return view('admin.auth.not-found', [
                'error_message' => 'This reset link is already expired. Please try again.',
            ]);
        }
        $admin = Admin::where('email', $password_reset['email'])->first();
        if (!$admin) {
            $password_reset->delete();
            return view('admin.auth.not-found', [
                'error_message' => 'Your reset request is invalid. Your account does not exist anymore.',
            ]);
        }
        return view('admin.auth.reset-password');
    }

    public function postReset($token, Request $request) {
        $password_reset = PasswordReset::type('admin')->where('token', $token)->first();
        if (!$password_reset) {
            return back()->with('error_message', 'Your reset request is invalid. Please check your inbox again.');
        }
        if (now()->diffInSeconds(new Carbon($password_reset['created_at'])) > 15 * 60) {
            $password_reset['token'] = null;
            $password_reset->save();
            return back()->with('error_message', 'This reset link is already expired. Please try again.');
        }
        $admin = Admin::where('email', $password_reset['email'])->first();
        if (empty($admin)) {
            $password_reset->delete();
            return back()->with('error_message', 'Your reset request is invalid. Your account does not exist anymore.');
        }
        $rule = [
            'password' => ['required', 'confirmed'],
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $admin['password'] = bcrypt($request['password']);
        $admin->save();
        /*try {
            $mail = MailTemplate::ofCategory('reset-notify')->first();
            $subject = str_replace('{Email}', $admin['email'], $mail['subject']);
            $mail_body = str_replace('{Email}', $admin['email'], $mail['body']);
            $mail_body = str_replace('{Link}', route('admin.login'), $mail_body);
            $data = [
                'subject' => $subject,
                'mail_body' => $mail_body,
            ];
            Mail::to($admin['email'])->send(new Template($data));
        } catch(\Exception $exception) {
        }*/
        return redirect()->route('admin.login')->with('info_message', 'Your password has been reset successfully.');
    }
}
