<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PatientMedicine;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateMedicine;
use Carbon\Carbon;

class MedicineController extends Controller
{
    public function viewMedicine(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $medicines = Medicine::orderBy('date', 'desc');

            $medicineCount = Medicine::select('*')->whereMonth('date', Carbon::now()->month);

            if($auth->role == 3) {
                $medicines = $medicines->where('place_id', $auth->work_address);
                $medicineCount = $medicineCount->where('place_id', $auth->work_address);
            }

            if($search = $request->search) {
                $medicines = $medicines->where('name', 'LIKE', '%' . $search . '%');
            }

            $medicineCount = $medicineCount->get();

            return Inertia::render('Medicines', [
                'auth'    => $auth,
                'options' => [
                    'places' => Place::get(),
                    'medicines' => $medicines->get(),
                    'search' => $search,
                    'medicineCount' => $medicineCount->sum('quantity'),
                    'medicineDispensed' => $medicineCount->sum('dispensed'),
                    'month' => date('F'),
                    'year' => date('Y')
                ]
            ]);
        }

        return redirect('/'); 
    }

    public function createMedicine(CreateMedicine $request)
    {
        $data = $request->toArray();

        $medicine = Medicine::where('name', $request->name)->whereMonth('date', Carbon::now()->month)->where('place_id', $request->place_id)->first();

        if($medicine) {
            $medicine->quantity += $request->quantity;
            $medicine->save();
        } else {
            Medicine::create($data);
        }

        return redirect()->back()->with('errors');
    }
}
