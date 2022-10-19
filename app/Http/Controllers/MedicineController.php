<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PatientMedicine;
use App\Models\BarangayMedicine;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DispenseMedicine;
use Carbon\Carbon;

class MedicineController extends Controller
{
    public function viewMedicine(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $medicines = Medicine::orderBy('name')->get();
            
            $barangayMedicines = BarangayMedicine::whereMonth('created_at', Carbon::now()->month);
            $patientMedicines = PatientMedicine::whereMonth('created_at', Carbon::now()->month);

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

            $barangayMedicines = $barangayMedicines->get();
            $patientMedicines = $patientMedicines->get();

            return Inertia::render('Medicines', [
                'auth'    => $auth,
                'options' => [
                    'places' => Place::get(),
                    'medicines' => $medicines,
                    'search' => $search,
                    'barangayMedicines' => $barangayMedicines,
                    'medicineCount' => $barangayMedicines->sum('quantity'),
                    'medicineDispensed' => $patientMedicines->sum('quantity'),
                    'month' => date('F'),
                    'year' => date('Y')
                ]
            ]);
        }

        return redirect('/'); 
    }

    public function dispenseBarangayMedicine(DispenseMedicine $request)
    {
        $data = $request->toArray();

        $arg = BarangayMedicine::where('medicine_id', $request->medicine_id)->whereMonth('created_at', Carbon::now()->month)->where('place_id', $request->place_id)->first();

        if($arg) {
            $arg->quantity += $request->quantity;
            $arg->save();
        } else {
            BarangayMedicine::create($data);
        }

        return redirect()->back()->with('errors');
    }
}
