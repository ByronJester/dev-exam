<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineUnit;
use App\Models\Patient;
use App\Models\PatientMedicine;
use App\Models\BarangayMedicine;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DispenseMedicine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    public function viewMedicine(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $medicines = Medicine::orderBy('name')->get();
            $availableMedicines = [];
            
            $barangayMedicines = BarangayMedicine::orderBy('created_at', 'desc')->whereMonth('created_at', Carbon::now()->month);
            $patientMedicines = PatientMedicine::orderBy('created_at', 'desc')->whereMonth('created_at', Carbon::now()->month);

            if($search = $request->search) {
                $barangayMedicines = $barangayMedicines->whereHas('medicine', function($q) use ($search){
                    $q->where('name', 'LIKE', '%'. $search . '%');
                });
            }

            if($auth->role == 3) {
                $barangayMedicines = $barangayMedicines->where('place_id', $auth->work_address);

                $patientMedicines = $patientMedicines->whereHas('patient', function($q) use ($auth){
                    $q->where('place_id', $auth->work_address);
                });
            }

            $patients = Patient::orderBy('created_at', 'desc');

            if($auth->role == 3) {
                $patients = $patients->where('is_rhu', false);

                $patientMedicines = $patientMedicines->where('is_individual', false);

                $availableMedicines = [];

                $barangayAvailableMedicines = BarangayMedicine::where('place_id', $auth->work_address)->whereMonth('created_at', Carbon::now()->month)->get();

                foreach ($barangayAvailableMedicines as $medicine) {
                    if($medicine->quantity > $medicine->dispensed) {
                        $availableMedicines[] = $medicine->medicine;
                    }
                }
            } else {
                $patients = $patients->where('is_rhu', true);

                $patientMedicines = $patientMedicines->where('is_individual', true);
            }

            $barangayMedicines = $barangayMedicines->get();
            $patientMedicines = $patientMedicines->get();

            return Inertia::render('Medicines', [
                'auth'    => $auth,
                'options' => [
                    'places' => Place::get(),
                    'medicines' => $auth->role == 3 ? $availableMedicines : $medicines,
                    'search' => $search,
                    'barangayMedicines' => $barangayMedicines,
                    'patientMedicines' => $patientMedicines,
                    'month' => date('F'),
                    'year' => date('Y'),
                    'patients' => $patients->get(),
                    'categories' => MedicineCategory::get(),
                    'units' => MedicineUnit::get(),
                ]
            ]);
        }

        return redirect('/'); 
    }

    public function dispenseBarangayMedicine(Request $request)
    {
        $auth = Auth::user();

        $validator = Validator::make($request->all(), [
            'place_id' => "sometimes|required",
            'patient_id' => "sometimes|required",
            'quantity' => "required|integer|min:1",
            'medicine_category_id' => "required",
            'medicine_unit_id' => "required",
            'dosage' => "required|integer|min:1",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $dispensed_type = $request->dispensed_type;

        if($dispensed_type == 'barangay'){
            $arg = BarangayMedicine::where('medicine_id', $request->medicine_id)->whereMonth('created_at', Carbon::now()->month)->where('place_id', $request->place_id)->first();
        } else {
            $arg = PatientMedicine::where('medicine_id', $request->medicine_id)->whereMonth('created_at', Carbon::now()->month)->where('patient_id', $request->patient_id)->first();
        }

        if($arg) {
            $arg->quantity += $request->quantity;
            $arg->save();
        } else {
            if($dispensed_type == 'barangay'){
                $data = $request->only(['place_id', 'quantity', 'medicine_id', 'medicine_category_id', 'medicine_unit_id', 'dosage']);

                BarangayMedicine::create($data);
            } else {
                $data = $request->only(['patient_id', 'quantity', 'medicine_id', 'medicine_category_id', 'medicine_unit_id', 'dosage']);

                $data['is_individual'] = true;

                if($auth->role == 3) {
                    $data['is_individual'] = false;
                }
                

                PatientMedicine::create($data);
            }
            
        }

        return response()->json(['status' => 200], 200);  
    }
}
