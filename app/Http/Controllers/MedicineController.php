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
use App\Models\MedicineStock;
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
                })
                ->orWhereHas('category', function($q) use ($search){
                    $q->where('name', 'LIKE', '%'. $search . '%');
                })
                ->orWhereHas('unit', function($q) use ($search){
                    $q->where('name', 'LIKE', '%'. $search . '%');
                });

                $patientMedicines = $patientMedicines->whereHas('medicine', function($q) use ($search){
                    $q->where('name', 'LIKE', '%'. $search . '%');
                })
                ->whereHas('category', function($q) use ($search){
                    $q->where('name', 'LIKE', '%'. $search . '%');
                })
                ->whereHas('unit', function($q) use ($search){
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
                $patients = $patients->where('is_rhu', false)->where('place_id', $auth->work_address);

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

            $medicineStocks = MedicineStock::where('quantity', '>', '0')->get();

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
                    'stocks' => $medicineStocks->pluck('medicine_id'),
                    'medicineStocts' => $auth->role == 3 ? $barangayMedicines: $medicineStocks, 
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
            return response()->json(['errors' => $validator->messages(), 'status' => 422, 'message' => null], 200);
        }

        $patient = null;

        if(!!$request->patient_id) {
            $patient = Patient::where('id', $request->patient_id)->first();
        }

        $dispensed_type = $request->dispensed_type;

        if($dispensed_type == 'barangay'){
            $arg = BarangayMedicine::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)
                ->whereMonth('created_at', Carbon::now()->month)->where('place_id', $request->place_id)->first();
        } else {
            $arg = PatientMedicine::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)
                ->whereMonth('created_at', Carbon::now()->month)->where('patient_id', $request->patient_id)->first();
        }

        if($arg) {
            if($dispensed_type == 'barangay'){
                $stock = MedicineStock::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                    ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)->first();

                if($stock){
                    if($stock->quantity >= $request->quantity) {
                        $arg->quantity += $request->quantity;
                        $arg->save(); 

                        $stock->quantity -= $request->quantity;
                        $stock->save();
                    } else {
                        return response()->json(['status' => 200, 'message' => 'Input quantity is greater than stock quantity.'], 200);  
                    }
                } else {
                    return response()->json(['status' => 200, 'message' => "There's no medicine stock found."], 200);  
                }
            } else {
                $stock = BarangayMedicine::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                    ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)
                    ->whereMonth('created_at', Carbon::now()->month)->first();

                if($patient && !!$patient->is_rhu) {
                    $stock = MedicineStock::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                        ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)->first();

                        if($stock->quantity >= $request->quantity) {
                            $arg->quantity += $request->quantity;
                            $arg->save(); 

                            $stock->quantity -= $request->quantity;
                            $stock->save();
                        } else {
                            return response()->json(['status' => 200, 'message' => 'Input quantity is greater than stock quantity.'], 200);  
                        }
                    
                } else {
                    if($stock){
                        if(($stock->quantity - $stock->dispensed) >= $request->quantity) {
                            $arg->quantity += $request->quantity;
                            $arg->save(); 
                        } else {
                            return response()->json(['status' => 200, 'message' => 'Input quantity is greater than stock quantity.'], 200);  
                        }
                    } else {
                        return response()->json(['status' => 200, 'message' => "There's no medicine stock found."], 200);  
                    }
                }
            }
        } else {
            if($dispensed_type == 'barangay'){
                $stock = MedicineStock::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                    ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)->first();

                if($stock){
                    if($stock->quantity >= $request->quantity) {
                        $data = $request->only(['place_id', 'quantity', 'medicine_id', 'medicine_category_id', 'medicine_unit_id', 'dosage']);

                        BarangayMedicine::create($data);

                        $stock->quantity -= $request->quantity;
                        $stock->save();
                    } else {
                        return response()->json(['status' => 200, 'message' => 'Input quantity is greater than stock quantity.'], 200);  
                    }
                } else {
                    return response()->json(['status' => 200, 'message' => "There's no medicine stock found."], 200);  
                }
            
            } else {
                $stock = BarangayMedicine::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                    ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage) 
                    ->whereMonth('created_at', Carbon::now()->month)->first();

                if($patient && !!$patient->is_rhu) {
                    $stock = MedicineStock::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
                        ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)->first();

                    if($stock){
                        if($stock->quantity >= $request->quantity) {
                            $data = $request->only(['patient_id', 'quantity', 'medicine_id', 'medicine_category_id', 'medicine_unit_id', 'dosage']);
    
                            $data['is_individual'] = true;

                            $stock->quantity -= $request->quantity;
                            $stock->save();
    
                            // if($auth->role == 2) {
                            //     $data['is_individual'] = true;
                            // }
                            
                            PatientMedicine::create($data); 
                        } else {
                            return response()->json(['status' => 200, 'message' => 'Input quantity is greater than stock quantity.'], 200);  
                        }
                    } else {
                        return response()->json(['status' => 200, 'message' => "There's no medicine stock found."], 200);  
                    }


                } else {
                    if($stock){
                        if(($stock->quantity - $stock->dispensed) >= $request->quantity) {
                            $data = $request->only(['patient_id', 'quantity', 'medicine_id', 'medicine_category_id', 'medicine_unit_id', 'dosage']);
    
                            $data['is_individual'] = false;
    
                            // if($auth->role == 2) {
                            //     $data['is_individual'] = true;
                            // }
                            
                            PatientMedicine::create($data); 
                        } else {
                            return response()->json(['status' => 200, 'message' => 'Input quantity is greater than stock quantity.'], 200);  
                        }
                    } else {
                        return response()->json(['status' => 200, 'message' => "There's no medicine stock found."], 200);  
                    }
                }
            }
        }

        return response()->json(['status' => 200, 'message' => null], 200);  

    }

    public function saveMedicineStock(Request $request)
    {
        $auth = Auth::user();

        $validator = Validator::make($request->all(), [
            'quantity' => "required|integer|min:1",
            'medicine_category_id' => "required",
            'medicine_unit_id' => "required",
            'dosage' => "required|integer|min:1",
            'medicine_id' => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $medicineStock = MedicineStock::where('medicine_id', $request->medicine_id)->where('medicine_category_id', $request->medicine_category_id)
            ->where('medicine_unit_id', $request->medicine_unit_id)->where('dosage', $request->dosage)->first();

        if($medicineStock) {
            $medicineStock->quantity += $request->quantity;
            $medicineStock->save();
        } else {
            MedicineStock::forceCreate($request->toArray());
        }

        return response()->json(['status' => 200], 200);  
    }

    public function getCategories(Request $request)
    {   
        $auth = Auth::user();
        $data = null;

        if($request->type == 'stock') {
            $data = MedicineStock::where('medicine_id', $request->medicine_id)->get();

            $data = $data->pluck('medicine_category_id');
        } else {
            $data = BarangayMedicine::where('medicine_id', $request->medicine_id)->where('place_id', $auth->work_address)->get();

            $data = $data->pluck('medicine_category_id');
        }

        return response()->json(['status' => 200, 'data' => $data], 200); 
    }

    public function getUnits(Request $request)
    {
        $auth = Auth::user();
        $data = null;

        if($request->type == 'stock') {
            $data = MedicineStock::where('medicine_id', $request->medicine_id)->get();

            $data = $data->pluck('medicine_unit_id');
        } else {
            $data = BarangayMedicine::where('medicine_id', $request->medicine_id)->where('place_id', $auth->work_address)->get();

            $data = $data->pluck('medicine_unit_id');
        }

        return response()->json(['status' => 200, 'data' => $data], 200); 
    }
}
