<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

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
            "Amoxicilin 500mg Capsule", "Amoxicilin 250mg Capsule", "Amoxicilin Drops", "Azithromycin 500mg Tablet", "Co-Amoxiclav 625mg Tablet", "Co-Amoxiclav 250mg/ 5ml", "Cefalexin 500mgTab", "Cefuroxime 250mg/5ml",
            "Cefuroxime 500mg CAP", "Cloxacilin 500mg CAP", "Cloxacilin 250mg Tablet", "Erythromycin 200mg/5ml", "Erythromycin 500mg Tab", "Metrodinazole 250mg/5ml", "Metrodinazole 500mg Tab", "Cetirizine 2.5mg/ml DROPS",
            "Cetirizine 5mg/5ml SYRUP", "Cetirizine 10mg TAB", "Diphenhydramine SYRUP", "Dipenhydramine 25mg CAP", "Loratadine 10mg TAB", "Lagundi 650mg TAB", "Lagundi 300mg TAB", "Lagundi 300mg/5ml SYR 120ml",
            "Butamirate citrate 50mg TAB", "Metoclopramide 5mg/5ml SYRUP", "HNBB 10mg TAB", "Dicloverine 10mg/5ml SYRUP", "Omeprazole 20mg CAP", "Omeprazole 40mg CAP", "Meclizine 25mg TAB", "Mefenamic acid 250mg CAP",
            "Mefenamic acid 500mg CAP", "Celecoxib 200mg", "Paracetamol DROPS", "Paracetamol 250mg/5ml SUSPENSION", "Paracetamol 125mg/5ml SUSP", "Paracetamol 500mg TAB", "Allopurinol 300mg TAB", "Colchicine 500mg TAB",
            "Salbutamol 2mg/5ml SYRUP", "Salbutamol 2mg TAB", "Salbutamol 2.5/2.5 NEBULES", "Salbutamol 100mcg MDI", "Salmeterol + Fluticasone 25mcg/125mcg MDI", "Salmeterol + Fluticasone 25mcg/250 mcg MDI",
            "Simvastasin 20mg TAB", "Simvastasim 40mg TAB", "Atorvasatim 40mg TAB", "Metformin 500mg TAB", "Amlopidine 5mg TAB", "Amlopidine 10mg TAB", "Losartan 50mg TAB", "Losartan 100mg TAB", "Captorpril 25mg TAB",
            "Carvedilol 12.5mg TAB", "Metropolol 50mg TAB", "Aspirin 80mg TAB", "Clopidorgel 75mg TAB", "Tranexamic Acid 500mg CAP", "Ascorbic Acid plus ZINC CAP", "Ascorbic Acid 120ml SYR", "Multivitamins CAP",
            "Multivitamins 120ml SYR", "Ferrous sulfate plus folic acid CAP", "Multivitamins drops", "Zinc Sulfate drops", "Zinc Sulfate SYR", "Oral Rehydration Salts (Sachet)"
        ];

        foreach ($medicines as $medicine) {
            Medicine::create([
                "name" => $medicine,
            ]);
        }
        
    }
}