<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\PatientForm;
use App\Models\PrenatalForm;
use App\Models\PatientMedicine;
use App\Models\BarangayMedicine;
use App\Models\Place;
use App\Models\Medicine;
use App\Http\Requests\CreatePatient;
use App\Http\Requests\CreatePatientForm;
use App\Http\Requests\DispenseMedicine;
use App\Http\Requests\SavePrenatal;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function viewPatients(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $patients = Patient::orderBy('created_at', 'desc');

            if($auth->role == 3) {
                $patients = $patients->where('place_id', $auth->work_address)->where('is_rhu', false);
            } 

            if($auth->role == 2) {
                $patients = $patients->where('is_rhu', true);
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

        if($auth->role == 2) {
            $data['is_rhu'] = true;
        }

        Patient::create($data);

        return redirect()->back()->with('errors');
    }

    public function viewPatient($id)
    {
        $auth = Auth::user();

        $patient = Patient::where('id', $id)->first();

        $tbAndPregForm = PatientForm::where('patient_id', $id)->get();
        $tbAndPregForm = $tbAndPregForm->toArray();

        $prenatalForm = PrenatalForm::where('patient_id', $id)->get();
        $prenatalForm = $prenatalForm->toArray();

        $forms = array_merge($tbAndPregForm, $prenatalForm);

        return Inertia::render('Patient', [
            'auth'    => $auth,
            'options' => [
                'patient' => $patient,
                'forms' => $forms
            ]
        ]);
    }

    public function viewPatientMedicine($id)
    {
        $auth = Auth::user();

        $patient = Patient::where('id', $id)->first();

        $availableMedicines = Medicine::get();

        if($auth->role == 3) {
            $availableMedicines = [];

            $barangayMedicines = BarangayMedicine::where('place_id', $patient->place_id)->whereMonth('created_at', Carbon::now()->month)->get();

            foreach ($barangayMedicines as $medicine) {
                if($medicine->quantity > $medicine->dispensed) {
                    $availableMedicines[] = $medicine;
                }
            }
        }
        
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

    public function createPrenatalForm(SavePrenatal $request)
    {
        $data = $request->except(['behavioral_risks', 'pyschological_risks', 'medical_risks', 'obstetrics_risks', 'name', 'description']);;

        if($request->behavioral_risks && count($request->behavioral_risks) > 0) {
            $data['behavioral_risks'] = json_encode($request->behavioral_risks);
        }

        if($request->pyschological_risks && count($request->pyschological_risks) > 0) {
            $data['pyschological_risks'] = json_encode($request->pyschological_risks);
        }

        if($request->medical_risks && count($request->medical_risks) > 0) {
            $data['medical_risks'] = json_encode($request->medical_risks);
        }

        if($request->obstetrics_risks && count($request->obstetrics_risks) > 0) {
            $data['obstetrics_risks'] = json_encode($request->obstetrics_risks);
        }

        if($request->id) {
            PrenatalForm::where('id', $request->id)->update($data);
        } else {
            PrenatalForm::forceCreate($data);
        }

        return redirect()->back()->with('errors');
    }

    public function dispenseMedicine(DispenseMedicine $request)
    {
        $data = $request->except(['place_id']);

        $patientMedicine = PatientMedicine::where('medicine_id', $request->medicine_id)->whereMonth('created_at', Carbon::now()->month)->where('patient_id', $request->patient_id)->first();

        if($patientMedicine) {
            $patientMedicine->quantity += $request->quantity;
            $patientMedicine->save();
        } else {
            PatientMedicine::create($data);
        }

        $barangayMedicine = BarangayMedicine::where('medicine_id', $request->medicine_id)->whereMonth('created_at', Carbon::now()->month)->where('place_id', $request->place_id)->first();

        if($barangayMedicine) {
            $barangayMedicine->quantity += $request->quantity;
            $barangayMedicine->save();
        } else {
            $barangayData = [
                "place_id" => $request->place_id,
                "medicine_id" => $request->medicine_id,
                "quantity" => $request->quantity,
            ];

            BarangayMedicine::create($barangayData);
        }

        return redirect()->back()->with('errors');


    }
}
