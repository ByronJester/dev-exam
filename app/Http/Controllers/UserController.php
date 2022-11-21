<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Place;
use App\Models\Medicine;
use App\Models\MedicineUnit;
use App\Models\MedicineCategory;
use App\Models\Vaccination;
use App\Models\BarangayMedicine;
use App\Models\PatientMedicine;
use App\Models\Patient;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

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

            if($auth->user_type == 'pharmacist') {
                return redirect('/medicines');
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
        // $data = [
        //     'email' => $request->email,
        //     'password' => $request->password,
        // ];

        // $user = User::where('email', $request->email)->first();

        // if(!$user) {
        //     return redirect()->back()->with('message', 'No account found.');
        // }

        // if(!$user->is_active) {
        //     return redirect()->back()->with('message', 'Your account is not verified.');
        // }

        // if(Auth::attempt($data)) {
        //     $auth = Auth::user();

        //     $canAccessUsers = ['admin', 'doctor', 'leader'];

        //     if($auth) {
        //         if(in_array($auth->user_type, $canAccessUsers)) {
        //             return redirect('/users');
        //         }

        //         if($auth->user_type == 'pharmacist') {
        //             return redirect('/medicines');
        //         }

        //         return redirect('/patients');
        //     }

        // } else {
        //     return redirect()->back()->with('message', 'Invalid Credentials.');
        // }

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            // return redirect()->back()->with('message', 'No account found.');
            return response()->json(['status' => 422, 'message' => 'No account found.' ], 200);  
        }

        if(!$user->is_active) {
            // return redirect()->back()->with('message', 'Your account is not verified.');
            return response()->json(['status' => 422, 'message' => 'Your account is not verified.' ], 200); 
        }

        if (Auth::attempt($data)) {
            // return redirect()->back()->with('message', 'success');
            return response()->json(['status' => 200, 'message' => 'success' ], 200); 
        } else {
            // return redirect()->back()->with('message', 'Invalid Credentials.');
            return response()->json(['status' => 422, 'message' => 'Invalid Credentials.' ], 200); 
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
            if(($request->user_type == 'doctor' || $request->user_type == 'midwife' || $request->user_type == 'nurse' || $request->user_type == 'pharmacist') && ($auth->role == 2 || $auth->role == 1)){
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

    public function viewMaitenance(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            return Inertia::render('Maintenance', [
                'auth'    => $auth,
                'options' => [
                    'medicines' => Medicine::get(),
                    'categories' => MedicineCategory::get(),
                    'units' => MedicineUnit::get(),
                    'vaccinations' => Vaccination::get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function saveMaintenance(Request $request)
    {
        $tab = $request->tab;

        $rules = [
            'name' => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        if($tab == 'medicine') {
            Medicine::create($request->only(['name']));
        }

        if($tab == 'category') {
            MedicineCategory::create($request->only(['name']));
        }

        if($tab == 'unit') {
            MedicineUnit::create($request->only(['name']));
        }

        if($tab == 'vaccination') {
            Vaccination::create($request->only(['name']));
        }

        return response()->json(['status' => 200], 200); 
    } 

    public function viewReports(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $patients = Patient::orderBy('created_at', 'desc')->where('user_id', $auth->id);

            if($auth->user_type == 'doctor') {
                $patients = Patient::orderBy('created_at', 'desc');
            }

            if($auth->user_type == 'midwife' && $auth->role == 2) {
                $patients = Patient::orderBy('created_at', 'desc')
                    ->whereHas('user', function (Builder $query) {
                        $query->whereIn('user_type', ['nurse', 'midwife']);
                    });
            }

            if($auth->user_type == 'leader') {
                $patients = Patient::orderBy('created_at', 'desc')
                    ->whereHas('user', function (Builder $query) {
                        $query->whereIn('user_type', ['member', 'midwife']);
                    });
            }

            $patientMedicines = PatientMedicine::orderBy('created_at', 'desc');

            if($auth->user_type == 'doctor') {
                $patientMedicines = $patientMedicines->where('is_individual', true);
            } else {
                if($auth->user_type == 'leader') {
                    $patientMedicines = $patientMedicines->where('is_individual', false);
                }
            }

            $barangayMedicines = BarangayMedicine::orderBy('created_at', 'desc');

            if($auth->user_type == 'leader') {
                $barangayMedicines = $barangayMedicines->where('place_id', $auth->work_address);
            } 

            return Inertia::render('Reports', [
                'auth'    => $auth,
                'options' => [
                    'barangayMedicines' => $barangayMedicines->get(),
                    'patientMedicines'  => $patientMedicines->get(),
                    'patients' => $patients->get()
                ]
            ]);
        }

        return redirect('/');
    }
} 
 