<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\PatientForm;
use App\Models\PrenatalForm;
use App\Models\PostnatalForm;
use App\Models\PatientMedicine;
use App\Models\BarangayMedicine;
use App\Models\Place;
use App\Models\Medicine;
use App\Models\NutritionForm;
use App\Models\DewormingForm;
use App\Models\Vaccination;
use App\Models\Allergy;
use App\Models\Medication;
use App\Models\HealthMaintenanceHistory;
use App\Models\VaccinationHistory;
use App\Models\DiseaseHistory;
use App\Models\Surgery;
use App\Models\WomenHealthHistory;
use App\Models\RiskyHabit;
use App\Models\VaccinationForm;
use App\Http\Requests\CreatePatient;
use App\Http\Requests\CreatePatientForm;
use App\Http\Requests\DispenseMedicine;
use App\Http\Requests\SavePrenatal;
use App\Http\Requests\SaveNutrition;
use App\Http\Requests\SaveDeworming;
use App\Http\Requests\SaveVaccination;
use App\Http\Requests\SavePostnatal;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function viewPatients(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $patients = Patient::orderBy('created_at', 'desc')->where('user_id', $auth->id);

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
        
        $data = $request->except(['image', 'id']);

        $data['user_id'] = $auth->id;

        if($auth->role == 2) {
            $data['is_rhu'] = true;
        }

        if($image = $request->image) {
                
            $path = public_path().'/images/uploads';

            $filename = time() . '_' . Str::random(8);

            $extension = $image->getClientOriginalExtension();
            
            $uplaod = $image->move($path, $filename . '.' . $extension);

            $data['image'] = $filename . '.' . $extension;
        }

        if($request->id) {
            Patient::where('id', $request->id)->update($data);
        } else {
            Patient::forceCreate($data);
        }

        

        return redirect()->back()->with('errors');
    }

    public function viewPatient($id, $isReport)
    {
        $auth = Auth::user();

        if($isReport == "false" || !$isReport) {
            $isReport = false;
        } else {
            $isReport = true;
        }

        $patient = Patient::where('id', $id)->first();

        $tbAndPregForm = PatientForm::where('patient_id', $id)->get();
        $tbAndPregForm = $tbAndPregForm->toArray();

        $prenatalForm = PrenatalForm::where('patient_id', $id)->get();
        $prenatalForm = $prenatalForm->toArray();

        $postnatalForm = PostnatalForm::where('patient_id', $id)->get();
        $postnatalForm = $postnatalForm->toArray();

        $nutritionForm = NutritionForm::where('patient_id', $id)->get();
        $nutritionForm = $nutritionForm->toArray();

        $dewormingForm = DewormingForm::where('patient_id', $id)->get();
        $dewormingForm = $dewormingForm->toArray();

        $vaccinationForm = VaccinationForm::where('patient_id', $id)->get();
        $vaccinationForm = $vaccinationForm->toArray();

        $forms = array_merge($tbAndPregForm, $prenatalForm, $postnatalForm, $nutritionForm, $dewormingForm, $vaccinationForm);

        $diseases = DiseaseHistory::where('patient_id', $id)->where('family', null)->get();
        $familyDiseases = DiseaseHistory::orderBy('family')->where('patient_id', $id)->where('family', '!=', null)->get();

        return Inertia::render('Patient', [
            'auth'    => $auth,
            'options' => [
                'patient' => $patient,
                'forms' => $forms,
                'vaccinationList' => Vaccination::get(),
                'isReport' => $isReport,
                'places' => Place::get(),
                'allergies' => Allergy::where('patient_id', $id)->get(),
                'medications' => Medication::where('patient_id', $id)->get(),
                'maintenance' => HealthMaintenanceHistory::where('patient_id', $id)->get(),
                'vaccinations' => VaccinationHistory::where('patient_id', $id)->get(),
                'diseases' => $diseases,
                'surgeries' => Surgery::where('patient_id', $id)->get(),
                'habits' => RiskyHabit::where('patient_id', $id)->get(),
                'families' => $familyDiseases,
                'womens' => WomenHealthHistory::where('patient_id', $id)->get(),
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
       $data = $request->except(['tb', 'date_issued' , 'display_year']);

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
        $data = $request->except(['behavioral_risks', 'pyschological_risks', 'medical_risks', 'obstetrics_risks', 'name', 'description', 'date_issued', 'display_year']);

        if(count($request->behavioral_risks) > 0) {
            $data['behavioral_risks'] = json_encode($request->behavioral_risks);
        } else {
            $data['behavioral_risks'] = json_encode($request->behavioral_risks);
        }

        if(count($request->pyschological_risks) > 0) {
            $data['pyschological_risks'] = json_encode($request->pyschological_risks);
        } else {
            $data['pyschological_risks'] = json_encode($request->pyschological_risks);
        }

        if(count($request->medical_risks) > 0) {
            $data['medical_risks'] = json_encode($request->medical_risks);
        } else {
            $data['medical_risks'] = json_encode($request->medical_risks);
        }

        if(count($request->obstetrics_risks) > 0) {
            $data['obstetrics_risks'] = json_encode($request->obstetrics_risks);
        } else {
            $data['obstetrics_risks'] = json_encode($request->obstetrics_risks);
        }

        if($request->id) {
            PrenatalForm::where('id', $request->id)->update($data);
        } else {
            $prenatal = PrenatalForm::orderBy('created_at', 'desc')->first();

            if($prenatal) {
                $data['id'] = $prenatal->id + 1;
            } else {
                $data['id'] = 1; 
            }

            PrenatalForm::create($data);
        }

        return redirect()->back()->with('errors');
    }

    public function createPostnatalForm(SavePostnatal $request)
    {
        $data = $request->except(['name', 'description', 'date_issued', 'display_year']);

        if($request->id) {
            PostnatalForm::where('id', $request->id)->update($data); 
        } else {
            $postnatal = PostnatalForm::orderBy('created_at', 'desc')->first();

            if($postnatal) {
                $data['id'] = $postnatal->id + 1;
            } else {
                $data['id'] = 1;
            }

            PostnatalForm::create($data);
            
        }

        return redirect()->back();
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

    public function createNutritionForm(SaveNutrition $request)
    {
        $data = $request->except(['name', 'description', 'date_issued', 'display_year']);

        if(!!$request->id) {
            NutritionForm::where('id', $request->id)->update($data);
        } else {
            NutritionForm::create($data);
        }

        return redirect()->back();
    }

    public function createDewormingForm(SaveDeworming $request)
    {
        $data = $request->except(['name', 'description', 'date_issued', 'display_year']);

        if(!!$request->id) {
            DewormingForm::where('id', $request->id)->update($data);
        } else {
            DewormingForm::create($data);
        }

        return redirect()->back();
    }

    public function createVaccinationForm(SaveVaccination $request)
    {
        $data = $request->except(['name', 'description', 'date_issued', 'display_year']);

        if(!!$request->id) {
            VaccinationForm::where('id', $request->id)->update($data);
        } else {
            VaccinationForm::create($data);
        }

        return redirect()->back();
    }

    public function viewMedicalHistory($id) 
    {
        $auth = Auth::user();

        $patient = Patient::where('id', $id)->first();

        $allergies = Allergy::where('patient_id', $id)->get();
        $medications = Medication::where('patient_id', $id)->get();
        $maintenance = HealthMaintenanceHistory::where('patient_id', $id)->get();
        $vaccinations = VaccinationHistory::where('patient_id', $id)->get();
        $diseases = DiseaseHistory::where('patient_id', $id)->where('family', null)->get();
        $familyDiseases = DiseaseHistory::orderBy('family')->where('patient_id', $id)->where('family', '!=', null)->get();
        $surgeries = Surgery::where('patient_id', $id)->get();
        $womens = WomenHealthHistory::where('patient_id', $id)->get();
        $habits = RiskyHabit::where('patient_id', $id)->get();

        return Inertia::render('History', [
            'auth'    => $auth,
            'options' => [
                'allergies' => $allergies,
                'medications' => $medications,
                'maintenance' => $maintenance,
                'vaccinations' => $vaccinations,
                'diseases' => $diseases,
                'familyDiseases' => $familyDiseases,
                'surgeries' => $surgeries,
                'womens' => $womens,
                'habits' => $habits,
                'patient' => $id,
                'gender' => $patient->gender
            ],
            'print' => [
                'allergies' => Allergy::where('patient_id', $id)->get(),
                'medications' => Medication::where('patient_id', $id)->get(),
                'maintenance' => HealthMaintenanceHistory::where('patient_id', $id)->get(),
                'vaccinations' => VaccinationHistory::where('patient_id', $id)->get(),
                'diseases' => DiseaseHistory::where('patient_id', $id)->where('family', null)->get(),
                'surgeries' => Surgery::where('patient_id', $id)->get(),
                'habits' => RiskyHabit::where('patient_id', $id)->get(),
                'families' => DiseaseHistory::orderBy('family')->where('patient_id', $id)->where('family', '!=', null)->get(),
                'womens' => WomenHealthHistory::where('patient_id', $id)->get(),
                'patient' => $patient
            ]
        ]);
    }

    public function saveAllergy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'allergy' => "required|alpha_spaces",
            'allergic_reaction' => "required|alpha_spaces"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            Allergy::where('id', $request->id)->update($data);
        } else {
            Allergy::forceCreate($data);
        }
        

        return response()->json(['status' => 200], 200);

    }

    public function saveMedication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medications' => "required|alpha_spaces",
            'dose' => "required|string",                           
            'times_per_day' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            Medication::where('id', $request->id)->update($data);
        } else {
            Medication::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);

    }

    public function saveMaintenanceHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => "required",
            'date' => "required",
            'facility' => 'required|alpha_spaces',
            'abnormal_result' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            HealthMaintenanceHistory::where('id', $request->id)->update($data);
        } else {
            HealthMaintenanceHistory::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);

    }

    public function saveVaccinationHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => "required",
            'date' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            VaccinationHistory::where('id', $request->id)->update($data);
        } else {
            VaccinationHistory::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);
    }

    public function saveDisease(Request $request) {
        $validator = Validator::make($request->all(), [
            'disease' => "required",
            'type' => "nullable|alpha_spaces",
            'status' => "required",
            'comment' => "required",
            'family' => "nullable"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['other', 'id']);

        if($request->id) {
            DiseaseHistory::where('id', $request->id)->update($data);
        } else {
            DiseaseHistory::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);
    }

    public function saveSurgery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => "required",
            'date' => "required",
            'facility' => "required|alpha_spaces",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            Surgery::where('id', $request->id)->update($data);
        } else {
            Surgery::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);
    }

    public function saveWomen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dlmc' => "required",
            'tnp' => "required",
            'complications' => "required|alpha_spaces",
            'afm' => "required|numeric",
            'am' => "required|numeric",
            'nlb' => "required|numeric",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            WomenHealthHistory::where('id', $request->id)->update($data);
        } else {
            WomenHealthHistory::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);
    }

    public function saveHabit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => "required",
            'status' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $data = $request->except(['id']);

        if($request->id) {
            RiskyHabit::where('id', $request->id)->update($data);
        } else {
            RiskyHabit::forceCreate($data);
        }

        return response()->json(['status' => 200], 200);
    }
}
