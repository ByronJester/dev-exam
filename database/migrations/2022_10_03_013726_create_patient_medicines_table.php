<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned()->comment('Foreign key from table patients');
            $table->bigInteger('medicine_id')->unsigned()->comment('Foreign key from table medicines');
            $table->bigInteger('medicine_category_id')->unsigned()->comment('Foreign key from table medicine_categories');
            $table->bigInteger('medicine_unit_id')->unsigned()->comment('Foreign key from table medicine_units');
            $table->integer('dosage');
            $table->integer('quantity');
            $table->boolean('is_individual')->default(false);
        
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->foreign('medicine_category_id')->references('id')->on('medicine_categories');
            $table->foreign('medicine_unit_id')->references('id')->on('medicine_units');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_medicines');
    }
}
