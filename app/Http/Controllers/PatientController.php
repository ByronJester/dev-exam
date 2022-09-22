<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\Place;
use App\Http\Requests\CreatePatient;

class PatientController extends Controller
{
    public function viewPatients(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $patients = Patient::orderBy('created_at', 'desc');

            if($auth->role == 3) {
                $patients = $patients->where('place_id', $auth->work_address);
            }

            if($search = $request->search) {
                $patients = $patients->where('name', 'LIKE', '%' . $search . '%');
            }

            return Inertia::render('Patients', [
                'auth'    => $auth,
                'options' => [
                    'patients' => $patients->get(),
                    'search' => $search,
                    'places' => Place::get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function createPatient(CreatePatient $request)
    {
        $data = $request->toArray();

        Patient::create($data);

        return redirect()->back()->with('errors');
    }
}
