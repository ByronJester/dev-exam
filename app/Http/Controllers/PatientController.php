<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\PatientForm;
use App\Models\PatientMedicine;
use App\Models\Place;
use App\Models\Medicine;
use App\Http\Requests\CreatePatient;
use App\Http\Requests\CreatePatientForm;
use App\Http\Requests\DispenseMedicine;
use Illuminate\Support\Str;

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
        $auth = Auth::user();
        
        $data = $request->toArray();

        $data['user_id'] = $auth->id;

        Patient::create($data);

        return redirect()->back()->with('errors');
    }

    public function viewPatient($id)
    {
        $auth = Auth::user();

        $patient = Patient::where('id', $id)->first();

        return Inertia::render('Patient', [
            'auth'    => $auth,
            'options' => [
                'patient' => $patient,
                'forms' => PatientForm::where('patient_id', $id)->get()
            ]
        ]);
    }

    public function viewPatientMedicine($id)
    {
        $auth = Auth::user();

        $patient = Patient::where('id', $id)->first();

        $availableMedicines = Medicine::where('place_id', $patient->place_id)->where('quantity' , '>', 0)->get();

        $patientMedicines = PatientMedicine::where('patient_id', $patient->id)->get();

        return Inertia::render('PatientMedicine', [
            'auth'    => $auth,
            'options' => [
                'patient' => $patient,
                'availableMedicines' => $availableMedicines,
                'patientMedicines' => $patientMedicines
            ]
        ]);
    }

    public function createPatientForm(CreatePatientForm $request) 
    {
       $data = $request->except(['tb']);;

        if($request->tb && count($request->tb) > 0) {
            $data['tb'] = json_encode($request->tb);
        }

        $data['reference'] = Str::random(8);

        if($request->id) {
            PatientForm::where('id', $request->id)->update($data);
        } else {
            PatientForm::create($data);
        }

        return redirect()->back()->with('errors');
    }

    public function dispenseMedicine(DispenseMedicine $request)
    {
        $medicine = Medicine::where('id', $request->medicine_id)->first();

        if($medicine) {
            $medicine->dispensed += $request->quantity;
            $medicine->save();
        }

        PatientMedicine::create($request->toArray());

        return redirect()->back()->with('errors');


    }
}
