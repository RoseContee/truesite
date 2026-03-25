<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function complaints($status = 'pending') {
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            abort(404);
        }
        $complaints = Complaint::status($status)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'status' => $status,
            'menu' => 'Complaints',
            'submenu' => $status,
        ]);
    }

    public function updateComplaint(Request $request) {
        $rule = [
            'complaint' => ['required', 'exists:complaints,id'],
            'status' => ['required', 'in:approved,rejected'],
        ];
        $validator = Validator::make($request->all(), $rule, [
            'complaint.required' => 'Please select the complaint you want to update.',
            'complaint.exists' => 'Please select the complaint you want to update.',
            'status.required' => 'Please select status.',
            'status.in' => 'Please select status.',
        ]);
        if ($validator->fails()) {
            $message = '';
            $errors = json_decode(json_encode($validator->errors()), true);
            foreach ($errors as $error) {
                $message .= implode('<br>', $error).'<br>';
            }
            return response([
                'error' => 'validation',
                'message' => $message,
            ]);
        }
        $complaint = Complaint::pending()->where('id', $request['complaint'])->first();
        if (!$complaint) {
            return response([
                'error' => 'validation',
                'message' => 'Please select the complaint you want to update.',
            ]);
        }
        $complaint['status'] = $request['status'];
        $complaint['reason'] = $request['reason'];
        $complaint->save();
        return response([
            'success' => true,
        ]);
    }

    public function users($status = null) {
        $users = User::orderBy('created_at', 'desc');
        if ($status == 'like') {
            $users = $users->where('like', 1);
        }
        $users = $users->get();
        return view('admin.users.index', [
            'users' => $users,
            'status' => $status,
            'menu' => 'Users',
            'submenu' => $status,
        ]);
    }

    public function likeUsers() {
        return $this->users('like');
    }

    public function profile() {
        return view('admin.profile', [
            'menu' => 'Profile',
        ]);
    }

    public function updateProfileEmail(Request $request) {
        $admin = auth('admin')->user();
        $rule = [
            'email' => ['required', 'email', 'unique:admins'],
            'password' => ['required'],
        ];
        $messages = [];
        if ($request['email'] == $admin['email']) {
            $messages['email.unique'] = 'Email cannot be same as current email.';
        }
        $validator = Validator::make($request->all(), $rule, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!Hash::check($request['password'], $admin['password'])) {
            return back()->withInput()->with('error_message', 'Password does not match current password.');
        }
        $admin['email'] = $request['email'];
        $admin->save();
        return back()->with('success_message', 'Your account email has been updated!');
    }

    public function updateProfilePassword(Request $request) {
        $admin = auth('admin')->user();
        $rule = [
            'old_password' => ['required'],
            'new_password' => ['required', 'confirmed'],
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!Hash::check($request['old_password'], $admin['password'])) {
            return back()->withInput()->with('error_message', 'Password does not match current password.');
        }
        $admin['password'] = bcrypt($request['new_password']);
        $admin->save();
        return back()->with('success_message', 'Your account password has been updated!');
    }
}
