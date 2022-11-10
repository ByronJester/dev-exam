<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Place;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

   public function loginView()
   {    
        $auth = Auth::user();

        $canAccessUsers = ['admin', 'doctor', 'leader'];

        if($auth) {
            if(in_array($auth->user_type, $canAccessUsers)) {
                return redirect('/users');
            }

            return redirect('/patients');
            
        }

        return Inertia::render('Login', [
            'data' => [
                'sample' => 1,
                'auth' => $auth
            ]
        ]);
   }

   public function loginAccount(Request $request)
   {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return redirect()->back()->with('message', 'No account found.');
        }

        if(!$user->is_active) {
            return redirect()->back()->with('message', 'Your account is not verified.');
        }

        if(Auth::attempt($data)) {
            $auth = Auth::user();

            $canAccessUsers = ['admin', 'doctor', 'leader'];

            if($auth) {
                if(in_array($auth->user_type, $canAccessUsers)) {
                    return redirect('/users');
                }

                return redirect('/patients');
            }

        } else {
            return redirect()->back()->with('message', 'Invalid Credentials.');
        }
   }

    public function logoutAccount()
    {
        Auth::logout();

        return redirect('/');;
    }

    public function viewUsers(Request $request)
    { 
        $auth = Auth::user();

        if($auth) {
            $users = User::orderBy('created_at', 'desc')->where('role', $auth->role)->whereNotIn('id', [$auth->id]);

            if($auth->role == 1) {
                $users = User::orderBy('created_at', 'desc')->whereIn('user_type', ['leader', 'doctor'])->whereNotIn('id', [$auth->id]);
            }

            if($search = $request->search) {
                $users = $users->where(function($q) use ($search){
                    $q->where('first_name', 'LIKE', '%' . $search . '%')->orWhere('last_name', 'LIKE', '%' . $search . '%');
                });
            }

            if($auth->role == 3) {
                $users = $users->where('work_address', $auth->work_address);
            }

            return Inertia::render('Users', [
                'auth'    => $auth,
                'options' => [
                    'users' => $users->get(),
                    'search' => $search,
                    'places' => Place::get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function changeStatus(Request $request)
    {
        User::where('id', $request->id)->update([
            'is_active' => $request->is_active
        ]);

        return redirect()->back();
    }

    public function saveUser(RegisterAccount $request)
    {
        $data = $request->toArray();
        $auth = Auth::user();

        $password = strtolower($request->last_name);
        $password = str_replace(' ', '_', $password);
        $data['password'] = Hash::make($password);
        
        if(($request->user_type == 'leader' || $request->user_type == 'midwife' || $request->user_type == 'member') && ($auth->role == 3 || $auth->role == 1)) {
            $data['role'] = 3;
        } else {
            if(($request->user_type == 'doctor' || $request->user_type == 'midwife' || $request->user_type == 'nurse') && ($auth->role == 2 || $auth->role == 1)){
                $data['role'] = 2;
            }
            
        }
        
        $saveUser = User::create($data);
        
        return redirect()->back()->with('errors'); 
    }

    public function changePassword(Request $request)
    {
        $auth = Auth::user();

        $rules = [
            'current_password' => "required|password",
            'new_password' => "required|min:8",
            'confirm_password' => "required|same:new_password|min:8",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $password = $request->new_password;

        User::where('id', $auth->id)->update([
            'password' => Hash::make($password)
        ]);

        return response()->json(['status' => 200], 200); 
    }
} 
 