<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrenatalFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prenatal_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned()->comment('Foreign key from table patients');
            $table->date('date_today');
            $table->string('provider_name');
            $table->string('npi');
            $table->string('billing_id');
            $table->string('tax_id');
            $table->string('provider_address');
            $table->string('city');
            $table->string('town');
            $table->string('zip');
            $table->string('provider_phone');
            $table->string('provider_fax');
            $table->string('member_name');
            $table->date('dob');
            $table->string('age');
            $table->string('member_address');
            $table->string('member_phone');
            $table->string('member_city');
            $table->string('member_town');
            $table->string('member_zip');
            $table->date('lmp');
            $table->string('g');
            $table->string('p');
            $table->string('edd');
            $table->date('first_prenatal');
            $table->date('recent_prenatal');
            $table->string('type_of_last_delivery');
            $table->date('last_delivery');
            $table->string('delivery_facility');
            $table->json('behavioral_risks');
            $table->json('pyschological_risks');
            $table->json('medical_risks');
            $table->json('obstetrics_risks');
            $table->string('ob_gyn');
            $table->date('date');
        
            $table->foreign('patient_id')->references('id')->on('patients');
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
        Schema::dropIfExists('prenatal_forms');
    }
}
