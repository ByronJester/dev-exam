<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('place_id')->unsigned()->comment('Foreign key from table places');
            $table->bigInteger('user_id')->unsigned()->nullable()->comment('Foreign key from table users');
            $table->string('name');
            $table->string('phone');
            $table->date('dob');
            $table->string('age');
            $table->string('gender');
            $table->string('civil_status')->default('Single');
            $table->string('philhealth')->nullable();
            $table->string('contact_person');
            $table->string('contact_person_address');
            $table->string('contact_person_phone');
            $table->longText('diagnosis')->nullable();
            $table->boolean('is_rhu')->default(false);

            
        
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('patients');
    }
}
