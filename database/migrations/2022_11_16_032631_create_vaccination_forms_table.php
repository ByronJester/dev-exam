<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccination_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned()->comment('Foreign key from table patients');
            $table->bigInteger('vaccination_id')->unsigned()->comment('Foreign key from table vaccinations');
            $table->string('fullname');
            $table->string('guardian_name');
            $table->date('dob');
            $table->string('age');
            $table->string('height');
            $table->string('weight');
            $table->string('bmi');
            
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('vaccination_id')->references('id')->on('vaccinations');
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
        Schema::dropIfExists('vaccination_forms');
    }
}
