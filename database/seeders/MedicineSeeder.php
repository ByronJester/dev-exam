<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineUnit;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medicines = [
            "Amoxicilin", "Azithromycin", "Co-Amoxiclav", "Cefalexin", "Cefuroxime",
            "Cloxacilin", "Erythromycin", "Metrodinazole",
            "Cetirizine", "Diphenhydramine", "Loratadine", "Lagundi",
            "Butamirate", "Metoclopramide", "HNBB", "Dicloverine", "Omeprazole", "Meclizine",
            "Mefenamic", "Celecoxib", "Paracetamol","Allopurinol", "Colchicine",
            "Salbutamol", "Salmeterol + Fluticasone",
            "Simvastasin", "Atorvasatim", "Metformin", "Amlopidine","Losartan", "Captorpril",
            "Carvedilol", "Metropolol", "Aspirin", "Clopidorgel", "Tranexamic", "Ascorbic Acid plus ZINC", "Ascorbic Acid", "Multivitamins",
            "Ferrous sulfate plus folic acid",  "Zinc Sulfate", "Oral Rehydration Salts (Sachet)"
        ];

        foreach ($medicines as $medicine) {
            Medicine::create([
                "name" => $medicine,
            ]);
        }

        $categories = [
            'Tablet', 'Capsule', 'Syrup', 'Nebules', 'MDI', 'Drops', 'Sachet'
        ];

        foreach ($categories as $category) {
            MedicineCategory::create([
                "category" => $category,
            ]);
        }

        $units = [
            'MG', 'ML', 'MCG'
        ];

        foreach ($units as $unit) {
            MedicineUnit::create([
                "unit" => $unit,
            ]);
        }
        
    }
}