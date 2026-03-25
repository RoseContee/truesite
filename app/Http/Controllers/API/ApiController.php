<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    private $limitation = 20;

    public function complaints(Request $request) {
        $user = User::where('email', $request['email'])->first();
        $domain = $request['domain'];
        $page = $request['page'] ?? 0;
        $reported = false;
        $complaints = Complaint::status(['pending', 'approved'])
            ->where('domain', $domain)
            ->orderBy('created_at', 'desc')
            ->offset($page * $this->limitation)
            ->limit($this->limitation)
            ->get();
        $data = [];
        foreach($complaints as $complaint) {
            if ($complaint['user_id'] == ($user['id'] ?? null)) {
                $reported = true;
            }
            if ($complaint['status'] != 'approved') continue;
            array_push($data, [
                'id' => $complaint['id'],
                'title' => $complaint['title'],
                'complaints' => $complaint['complaints'],
                'image' => file_exists(public_path($complaint['screenshot'])) ? asset('public/'.$complaint['screenshot']) : '',
            ]);
        }
        return [
            'status' => 200,
            'email' => $user['email'] ?? null,
            'complaints' => $data,
            'page' => $page + 1,
            'limit' => $this->limitation,
            'is_more' => count($data) == $this->limitation,
            'reported' => $reported,
            'liked' => !empty($user['like'] ?? 0),
        ];
    }

    public function authGoogle(Request $request) {
        $update = false;
        if (!($user = User::where('email', $request['email'])->first())) {
            $update = true;
            $user = new User();
            $user['email'] = $request['email'];
            $user['email_verified_at'] = now();
            $user['password'] = bcrypt(Str::random(8));
            $user['like'] = 0;
        }
        if (!$user['name']) {
            $update = true;
            $user['name'] = $request['name'];
        }
        if (!$user['avatar']) {
            $update = true;
            $user['avatar'] = $request['avatar'];
        }
        if (!$user['google_id']) {
            $update = true;
            $user['google_id'] = $request['google_id'];
        }
        if ($update) $user->save();
        $complaint = Complaint::status(['pending', 'approved'])
            ->where('domain', $request['domain'])
            ->count();
        return [
            'status' => 200,
            'email' => $user['email'] ?? null,
            'reported' => !empty($complaint),
            'liked' => !empty($user['like'] ?? 0),
        ];
    }

    public function like(Request $request) {
        if (!($user = User::where('email', $request['email'])->first())) {
            return response([
                'status' => 401,
                'error' => 'Email does not exist.',
            ]);
        }
        if (!$user['like']) {
            $user['like'] = 1;
            $user->save();
        }
        return response([
            'status' => 200,
        ]);
    }

    public function reportFraud(Request $request) {
        $rule = [
            'domain' => ['required'],
            'title' => ['required'],
            'complaint' => ['required'],
        ];
        if ($request->hasFile('screenshot')) {
            $rule['screenshot'] = ['image'];
        }
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            $message = '';
            $errors = json_decode(json_encode($validator->errors()), true);
            foreach ($errors as $error) {
                $message .= implode('<br>', $error).'<br>';
            }
            return response([
                'status' => 400,
                'error' => $message,
            ]);
        }
        if (!($user = User::where('email', $request['email'])->first())) {
            return response([
                'status' => 401,
                'error' => 'Email does not exist.',
            ]);
        }
        if (!$user->complaints()->where('domain', $request['domain'])->status(['pending', 'approved'])->count()) {
            $screenshot = null;
            if ($request->hasFile('screenshot')) {
                $screenshot = 'uploads/'.$request->file('screenshot')->store('report/'.$user['id']);
            }
            $user->complaints()->create([
                'domain' => $request['domain'],
                'title' => $request['title'],
                'complaints' => $request['complaint'],
                'screenshot' => $screenshot,
                'status' => 'pending',
            ]);
        }
        return response([
            'status' => 200,
        ]);
    }
}
