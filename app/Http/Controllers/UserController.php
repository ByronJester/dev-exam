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
use App\Models\Log;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $user = User::where('username', $request->username)->first();

        if(!$user) {
            return response()->json(['status' => 422, 'message' => 'No account found.' ], 200);  
        }

        if(!$user->is_active) {
            return response()->json(['status' => 422, 'message' => 'Your account is not verified.' ], 200); 
        }

        if (Auth::attempt($data)) {
            $auth = Auth::user();

            Log::forceCreate([
                'user_id' => $auth->id,
                'description' => $auth->first_name . ' ' . $auth->last_name . ' has logged in.'
            ]);

            return response()->json(['status' => 200, 'message' => 'success' ], 200); 
        } else {
            return response()->json(['status' => 422, 'message' => 'Invalid Credentials.' ], 200); 
        }
   }

    public function logoutAccount()
    {
        $auth = Auth::user();

        $save = Log::forceCreate([
            'user_id' => $auth->id,
            'description' => $auth->first_name . ' ' . $auth->last_name . ' has logged out.'
        ]);

        if($save) {
            Auth::logout();
        }

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
                    'users' => $users->where('is_active', true)->get(),
                    'search' => $search,
                    'places' => Place::orderBy('name')->get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function viewArchives(Request $request)
    { 
        $auth = Auth::user();

        if($auth) {
            $users = User::orderBy('created_at', 'desc')->where('role', $auth->role)->whereNotIn('id', [$auth->id])->where('is_active', false);

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

            return Inertia::render('Archives', [
                'auth'    => $auth,
                'options' => [
                    'users' => $users->where('is_active', false)->get(),
                    'search' => $search,
                    'places' => Place::orderBy('name')->get()
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
        $data = $request->except(['image', 'id']);
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

        if($image = $request->image) {
                
            $path = public_path().'/images/uploads';

            $filename = time() . '_' . Str::random(8);

            $extension = $image->getClientOriginalExtension();
            
            $uplaod = $image->move($path, $filename . '.' . $extension);

            $data['image'] = $filename . '.' . $extension;
        }
        
        if($request->id) {
            $saveUser = User::where('id', $request->id)->update($data);
        } else {
            $saveUser = User::forceCreate($data);
        }
        
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
                    'medicines' => Medicine::orderBy('name')->get(),
                    'categories' => MedicineCategory::orderBy('name')->get(),
                    'units' => MedicineUnit::orderBy('name')->get(),
                    'vaccinations' => Vaccination::orderBy('name')->get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function saveMaintenance(Request $request)
    {
        $tab = $request->tab;
        $id = $request->id;

        $rules = [
            'name' => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        if($tab == 'medicine') {
            if($id) {
                Medicine::where('id', $id)->update(['name' => $request->name]);
            } else {
                Medicine::create($request->only(['name']));
            }
            
        }

        if($tab == 'category') {
            if($id) {
                MedicineCategory::where('id', $id)->update(['name' => $request->name]);
            } else {
                MedicineCategory::create($request->only(['name']));
            }
        }

        if($tab == 'unit') {
            if($id) {
                MedicineUnit::where('id', $id)->update(['name' => $request->name]);
            } else {
                MedicineUnit::create($request->only(['name']));
            }
        }

        if($tab == 'vaccination') {
            if($id) {
                Vaccination::where('id', $id)->update(['name' => $request->name]);
            } else {
                Vaccination::create($request->only(['name']));
            }
        }

        return response()->json(['status' => 200], 200); 
    } 

    public function viewReports(Request $request)
    {
        $auth = Auth::user();

        $patientData = [];

        if($auth) {
            $patients = Patient::orderBy('created_at', 'desc')->where('user_id', $auth->id);

            if($auth->user_type == 'doctor') {
                $patients = Patient::orderBy('first_name');
            }

            if($auth->user_type == 'midwife' && $auth->role == 2) {
                $patients = Patient::orderBy('first_name')
                    ->whereHas('user', function (Builder $query) {
                        $query->whereIn('user_type', ['nurse', 'midwife']);
                    });
            }

            if($auth->user_type == 'leader') {
                $patients = Patient::orderBy('first_name')
                    ->where('place_id', $auth->work_address)
                    ->whereHas('user', function (Builder $query) use ($auth) {
                        $query->whereIn('user_type', ['leader', 'member', 'midwife'])->where('role', $auth->role);
                    });
            }

            $patientMedicines = PatientMedicine::orderBy('created_at', 'desc');

            if($auth->user_type == 'pharmacist') {
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

            $date_start = $request->date_start;
            $date_end = $request->date_end;
            $age_start = $request->age_start;
            $age_end = $request->age_end;
            $gender = $request->gender;
            $formType = $request->formType;
            $barangay = $request->barangay;

            if($date_start && $date_end){
                $from = Carbon::parse($date_start)->startOfDay()->toDateTimeString(); // 2018-09-29 00:00:00
                $to = Carbon::parse($date_end)->endOfDay()->toDateTimeString(); // 2018-09-29 00:00:00

                $patients = $patients->whereBetween('created_at', [$from, $to]);
            }

            if($gender){
                $patients = $patients->where('gender', $gender);
            }
            
            $patientData = $patients->get();

            $isFiltered = false;

            if($age_start && $age_end){
                $patientData = $patientData->filter(function ($value, $key) use ($age_start, $age_end) {
                    return $value->age >= $age_start && $value->age <= $age_end;
                });

                $isFiltered = true;
            }

            if($formType) {
                $patientData = $patientData->filter(function ($value, $key) use ($formType) {
                    return in_array($formType, $value->consultation_form);
                });

                $isFiltered = true;
            }

            if($barangay) {
                $patientData = $patientData->filter(function ($value, $key) use ($barangay) {
                    return strtolower($value->barangay) == strtolower($barangay);
                });

                $isFiltered = true;
            }

            $now = Carbon::now();

            $reportCount = !!$isFiltered ? $patientData->count() : count($patientData);

            $page = $request->page ? $request->page : 1;
            $perPage = 10;
            $pageCount = ceil($reportCount / $perPage);

            return Inertia::render('Reports', [
                'auth'    => $auth,
                'options' => [
                    'barangayMedicines' => $barangayMedicines->get(),
                    'patientMedicines'  => $patientMedicines->get(),
                    'patients' => $patientData->forPage($page, $perPage),
                    'places' => Place::orderBy('name')->get(),
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'age_start' => $age_start,
                    'age_end' => $age_end,
                    'gender' => $gender,
                    'formType' => $formType,
                    'barangay' => $barangay,
                    'now' => $now->isoFormat('LL'),
                    'reportCount' => $reportCount,
                    'pageCount' => $pageCount,
                    'page' => $page
                ]
            ]);
        }

        return redirect('/');
    }

    public function viewTrails(Request $request)
    {
        $auth = Auth::user();

        $logs = Log::orderBy('created_at', 'desc')->get();

        return Inertia::render('Trails', [
            'auth'    => $auth,
            'options' => [
                'logs' => $logs
            ]
        ]);
    }

    public function alive(Request $request)
    {
        return response()->json(['status' => 200], 200);
    }
} 
 