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

        $password = Str::random(10);
        $data['password'] = Hash::make($password);
        $data['role'] = Auth::user()->role;

        $saveUser = User::create($data);

        if($saveUser) {
            $this->sendSms($saveUser->phone, 
                'Hi ' . $saveUser->first_name . ' ' . $saveUser->last_name . ' Your password for account with email ' . $saveUser->email . ' is ' . $password . '.'
            );
        }
        
        return redirect()->back()->with('errors');
    }
} 
 