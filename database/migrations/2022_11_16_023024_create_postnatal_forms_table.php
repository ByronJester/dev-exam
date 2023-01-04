<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostnatalFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postnatal_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patient_id')->unsigned()->comment('Foreign key from table patients');
            $table->longText('hospital_name');
            $table->longText('clients_name');
            $table->longText('address');
            $table->longText('occupation');
            $table->longText('type_of_delivery');
            $table->longText('mode_of_delivery');
            $table->longText('total_hours_of_labor');
            $table->longText('postnatal_day');
            $table->longText('register_no');
            $table->date('doa');
            $table->longText('religion');
            $table->longText('gestational_age');
            $table->date('lmp');
            $table->longText('new_born_sex');
            $table->date('dob');
            $table->longText('age');
            $table->longText('education');
            $table->longText('obstetrical_score');
            $table->longText('chief_complain')->nullable();
            $table->date('edd');
            $table->longText('newborn_weight');
            $table->longText('bad_habits')->nullable();
            $table->longText('elimination_pattern')->nullable();
            $table->longText('activity_and_exercise')->nullable();
            $table->longText('menarche_age');
            $table->longText('period');
            $table->longText('nutritional_pattern')->nullable();
            $table->longText('sleeping_pattern')->nullable();
            $table->longText('cycle');
            $table->longText('amount_of_blood_loss');
            $table->longText('duration_of_marriage')->nullable();
            $table->longText('family_planning_method_adopted')->nullable();
            $table->longText('diseases')->nullable();
            $table->longText('genitics_condition')->nullable();
            $table->longText('gravidity');
            $table->longText('parity');
            $table->longText('miscarriages');
            $table->longText('terminations');
            $table->longText('previous_pregnancy');
            $table->longText('length_of_pregnancy');
            $table->longText('induction');        
            
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
        Schema::dropIfExists('postnatal_forms');
    }
}
