<template>
    <div class="w-screen h-screen --main">
        <Navigation :auth="auth">
            <div class="px-4 pt-12">
                <div class="w-full">
                    <span class="text-2xl">
                        <i class="fa-solid fa-arrow-left mr-2 cursor-pointer" @click="back()"></i>  {{ options.isReport ? 'Patient Report' : 'Consultation Forms' }}
                    </span>

                    <span class="text-2xl float-right font-bold">
                        {{ patient.name }} ({{ patient.place.name }})
                    </span>
                </div>

                <div class="w-full mt-10" v-if="!options.isReport">
                    <div class="w-full inline-block">
                        <select v-model="formName"
                            style="width: 230px; height: 30px; border: 1px solid black"
                        >
                            <option value="Tuberculosis Symptom Form" v-if="auth.user_type == 'doctor' || auth.user_type == 'leader' || auth.user_type == 'member'">
                                TB-Dots Form
                            </option>

                            <option value="Pregnancy Form" v-if="(auth.user_type == 'nurse' || auth.user_type == 'midwife') && patient.gender != 'Male'">
                                Pregnancy Form
                            </option>

                            <option value="Prenatal Registration Form" v-if="(auth.user_type == 'nurse' || auth.user_type == 'midwife') && patient.gender != 'Male'">
                                Prenatal Registration Form
                            </option>

                            <option value="Postnatal Registration Form" v-if="(auth.user_type == 'nurse' || auth.user_type == 'midwife') && patient.gender != 'Male'">
                                Postnatal Registration Form
                            </option>

                            <option value="Nutrition Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Nutrition Form
                            </option>
                            
                            <option value="Vaccination Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Vaccination Form
                            </option>
                            <option value="Deworming Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Deworming Form
                            </option>
                        </select>

                        <button style="height: 30px; border: 1px solid black; background: #003865; padding: 0px 20px 0px 20px" class="text-white"
                            @click="activeForm = formName; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null;" 
                        >
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                
                <div class="w-full mt-2" v-if="!!options.isReport">

                    <button class="--view__profile my-2 mr-1" @click="printProfile()">
                        <i class="fa-solid fa-print mr-1"></i> Profile
                    </button>

                    <button class="--view__profile my-2 mr-1" @click="printHistory()">
                        <i class="fa-solid fa-print mr-1"></i> Medical History 
                    </button>

                    <select name="yearpicker" id="yearpicker"
                        style="border: 1px solid black; width: 100px; height: 40px"
                        class="ml-2 text-center float-right"
                        v-model="selectedYear"
                    >
                        <option :value="year" v-for="year in years" :key="year"> {{ year }}</option>
                    </select>
                    
                </div>

                <VueHtml2pdf
                    :show-layout="false"
                    :float-layout="true"
                    :enable-download="true"
                    :preview-modal="true"
                    :paginate-elements-by-height="2000"
                    :filename="Math.random().toString(36).slice(2)" 
                    :pdf-quality="2"
                    :manual-pagination="false"
                    pdf-format="a4"
                    pdf-orientation="landscape"
                    pdf-content-width="100%"
                    ref="history"
                >
                    <section slot="pdf-content">
                        <div class="w-full px-5 py-5">
                            <div class="w-full text-2xl font-bold">
                                Patient's Medical History

                                <span class="float-right">
                                    {{ patient.name }} ({{ patient.place.name }})
                                </span>
                            </div>

                            <table style="width:100%" class="mt-5">
                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Allergies
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Allergy</td>
                                    <td>
                                        <p v-for="allergy in options.allergies" :key="allergy.id">
                                            {{allergy.allergy}}<span v-if="options.allergies.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Allergic Reaction</td>
                                    <td>
                                        <p v-for="allergy in options.allergies" :key="allergy.id">
                                            {{allergy.allergic_reaction}}<span v-if="options.allergies.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Medications
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Medications</td>
                                    <td>
                                        <p v-for="medication in options.medications" :key="medication.id">
                                            {{medication.medications}}<span v-if="options.medications.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dose</td>
                                    <td>
                                        <p v-for="medication in options.medications" :key="medication.id">
                                            {{medication.dose}}<span v-if="options.medications.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Times Per Day</td>
                                    <td>
                                        <p v-for="medication in options.medications" :key="medication.id">
                                            {{medication.times_per_day}}<span v-if="options.medications.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Health Maintenance
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <p v-for="m in options.maintenance" :key="m.id">
                                            {{m.type}}<span v-if="options.maintenance.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>
                                        <p v-for="m in options.maintenance" :key="m.id">
                                            {{m.date}}<span v-if="options.maintenance.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Facility</td>
                                    <td>
                                        <p v-for="m in options.maintenance" :key="m.id">
                                            {{m.facility}}<span v-if="options.maintenance.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Abnormal Result</td>
                                    <td>
                                        <p v-for="m in options.maintenance" :key="m.id">
                                            {{m.abnormal_result}}<span v-if="options.maintenance.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Vaccination
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <p v-for="vaccination in options.vaccinations" :key="vaccination.id">
                                            {{vaccination.type}}<span v-if="options.vaccinations.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>
                                        <p v-for="vaccination in options.vaccinations" :key="vaccination.id">
                                            {{vaccination.date}}<span v-if="options.vaccinations.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Disease
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Disease</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.disease}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.type}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.status}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Comment</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.comment}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Surgery
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <p v-for="surgery in options.surgeries" :key="surgery.id">
                                            {{surgery.type}}<span v-if="options.surgeries.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>
                                        <p v-for="surgery in options.surgeries" :key="surgery.id">
                                            {{surgery.date}}<span v-if="options.surgeries.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Facility</td>
                                    <td>
                                        <p v-for="surgery in options.surgeries" :key="surgery.id">
                                            {{surgery.facility}}<span v-if="options.surgeries.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Risky Habits
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <p v-for="habit in options.habits" :key="habit.id">
                                            {{habit.type}}<span v-if="options.habits.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <p v-for="habit in options.habits" :key="habit.id">
                                            {{habit.status}}<span v-if="options.habits.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Women's Health
                                    </td> 
                                </tr>
                                <tr>
                                    <td>DLMC</td>
                                    <td>
                                        <p v-for="women in options.womens" :key="women.id">
                                            {{women.dlmc}}<span v-if="options.womens.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>TNP</td>
                                    <td>
                                        <p v-for="women in options.womens" :key="women.id">
                                            {{women.tnp}}<span v-if="options.womens.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Complications</td>
                                    <td>
                                        <p v-for="women in options.womens" :key="women.id">
                                            {{women.complications}}<span v-if="options.womens.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>AFM</td>
                                    <td>
                                        <p v-for="women in options.womens" :key="women.id">
                                            {{women.afm}}<span v-if="options.womens.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>AM</td>
                                    <td>
                                        <p v-for="women in options.womens" :key="women.id">
                                            {{women.am}}<span v-if="options.womens.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>NLB</td>
                                    <td>
                                        <p v-for="women in options.womens" :key="women.id">
                                            {{women.nlb}}<span v-if="options.womens.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>



                                <tr>
                                    <td style="background: #366422" class="text-center text-white text-2xl font-bold" colspan="2">
                                        Family
                                    </td> 
                                </tr>
                                <tr>
                                    <td>Family Member</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.family}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Disease</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.disease}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.type}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.status}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Comment</td>
                                    <td>
                                        <p v-for="disease in options.diseases" :key="disease.id">
                                            {{disease.comment}}<span v-if="options.diseases.length > 1">,</span>
                                        </p>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                    </section>
                </VueHtml2pdf>

                <VueHtml2pdf
                    :show-layout="false"
                    :float-layout="true"
                    :enable-download="true"
                    :preview-modal="true"
                    :paginate-elements-by-height="1000"
                    :filename="Math.random().toString(36).slice(2)" 
                    :pdf-quality="2"
                    :manual-pagination="false"
                    pdf-format="a4"
                    pdf-orientation="portrait"
                    pdf-content-width="100%"
                    ref="profile"
                >
                    <section slot="pdf-content">
                        <div class="w-full p-5">
                            <div class="w-full flex flex-col">
                                <div class="w-full  mt-3 mb-3 flex flex-row">
                                    <div style="width: 80%">
                                        <div class="w-full text-center">
                                            <p style="font-weight: 700" class="text-xl ml-32">
                                                Patient's Profile
                                            </p>

                                            <p style="font-weight: 500" class="text-lg ml-32">
                                                Republic of the Philippines<br>
                                                Province of Batangas <br>
                                                Municipality Health of Balayan Batangas
                                            </p>
                                        </div>
                                    </div>

                                    <div style="width: 20%">
                                        <img :src="patient.image ? '/images/uploads/'+ patient.image : '/images/dp.jpg'"
                                            style="width: 150px; height: 150px; border: 1px solid black"
                                            class="float-right"
                                        />
                                    </div>
                                </div>

                                <div class="my-1">
                                    <label class="text-bold">Fullname:</label><br>
                                    <input type="text" class="--input mt-2" v-model="patient.name" :disabled="true">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('name', saveError)}} </span>
                                </div>

                                <div class="my-1" v-if="auth.role != 3">
                                    <label for="cars">Barangay:</label><br>
                                    <select class="--input mt-2" v-model="patient.place_id" :disabled="true">
                                        <option v-for="place in options.places" :key="place.id"
                                            :value="place.id"
                                        >
                                            {{ place.name }}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('place_id', saveError)}} </span>
                                </div>

                                <div class="my-1 w-full flex flex-row">
                                    <div class="w-full">
                                        <label class="text-bold">Contact No.:</label><br>
                                        <input type="text" class="--input mt-2" v-model="patient.phone" :disabled="true">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('phone', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row">
                                    <div class="my-1 mx-1 w-full">
                                        <label class="text-bold">Date of Birth:</label><br>
                                        <input type="date" class="--input mt-2" v-model="patient.dob" :disabled="true">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('dob', saveError)}} </span>
                                    </div>

                                    <div class="my-1 mx-1 w-full">
                                        <label class="text-bold">Age:</label><br>
                                        <input type="text" class="--input mt-2" v-model="patient.age" :disabled="true">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('age', saveError)}} </span>
                                    </div>
                                </div>
                                
                                <div class="w-full flex flex-row">
                                    <div class="my-1 mx-1 w-full">
                                        <label for="cars">Gender:</label><br>
                                        <select class="--input mt-2" v-model="patient.gender" :disabled="true">
                                            <option :value="'Male'">
                                                Male
                                            </option>

                                            <option :value="'Female'">
                                                Female
                                            </option>
                                        </select>
                                        <span class="text-xs text-red-500 ml-2">{{validationError('gender', saveError)}} </span>
                                    </div>


                                    <div class="my-1 mx-1 w-full">
                                        <label for="cars">Civil Status:</label><br>
                                        <select class="--input mt-2" v-model="patient.civil_status" :disabled="true">
                                            <option :value="'Single'">
                                                Single
                                            </option>

                                            <option :value="'Maried'">
                                                Maried
                                            </option>

                                            <option :value="'Separated'">
                                                Separated
                                            </option>

                                            <option :value="'Widowed'">
                                                Widowed
                                            </option>
                                        </select>
                                        <span class="text-xs text-red-500 ml-2">{{validationError('civil_status', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row">
                                    <div class="my-1 mx-1">
                                        <label class="text-bold">Contact Person:</label><br>
                                        <input type="text" class="--input mt-2" v-model="patient.contact_person" :disabled="true">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('contact_person', saveError)}} </span>
                                    </div>

                                    <div class="my-1 mx-1">
                                        <label class="text-bold">Contact Person Address:</label><br>
                                        <input type="text" class="--input mt-2" v-model="patient.contact_person_address" :disabled="true">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('contact_person_address', saveError)}} </span>
                                    </div>

                                    <div class="my-1 mx-1">
                                        <label class="text-bold">Contact Person #:</label><br>
                                        <input type="text" class="--input mt-2" v-model="patient.contact_person_phone" :disabled="true">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('contact_person_phone', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="my-1" v-if="auth.user_type == 'doctor'">
                                    <label class="text-bold">Diagnosis:</label><br>
                                    <input type="text" class="--input mt-2" v-model="formData.diagnosis" :disabled="true">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('philhealth', saveError)}} </span>
                                </div>

                                <div class="my-1">
                                    <label class="text-bold">Philhealth ID #:</label><br>
                                    <input type="text" class="--input mt-2" v-model="patient.philhealth" :disabled="true">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('philhealth', saveError)}} </span>
                                </div>
                            </div>
                        </div>
                    </section>
                </VueHtml2pdf> 

                <div class="w-full mt-2">
                    <Table :columns="columns" :rows="forms" :keys="keys" :selected.sync="selectedForm"
                        v-if="!activeForm && !selectedForm"
                    />

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="1000"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="tb-dots"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div class="w-full text-2xl font-bold mb-5">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>

                                <p class="mb-2 font-bold">
                                    Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung mayroon ng alinman sa mga sumusunod na sintomas at angkop na tagal ng sintomas na nararanasan:
                                </p>

                                <div class="w-full flex flex-row text-sm">
                                    <div class="flex flex-col"
                                        style="width: 70%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="1" v-model="formData.tb">
                                            <label> Ubo</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="2" v-model="formData.tb">
                                            <label> Pag-ubo na may kasamang dugo</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="3" v-model="formData.tb">
                                            <label> Lagnat na di maipaliwanag ang sanhi</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="4" v-model="formData.tb">
                                            <label> Pamamayat na di maipaliwanag ang sanhi</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="5" v-model="formData.tb">
                                            <label> Labis na pagpapawis sa hapon o gabi na walang kinalaman sa init ng panahon</label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col"
                                        style="width: 10%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="6" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="7" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="8" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="9" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="10" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                    </div>

                                    <div class="flex flex-col"
                                        style="width: 10%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="11" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="12" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="13" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="14" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="15" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col"
                                        style="width: 10%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="16" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="17" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="18" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="19" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="20" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        
                                    </div>
                                </div>

                                <!--  -->

                                <p class="mb-2 mt-4 font-bold">
                                    Karagdagang tanong para sa mga batang edad 0-14:
                                </p>

                                <div class="w-full flex flex-row text-sm">
                                    <div class="flex flex-col"
                                        style="width: 70%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="21" v-model="formData.tb">
                                            <label> Ubo o kahirapan sa paghinga</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="22" v-model="formData.tb">
                                            <label> Mabilis mapagod</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="23" v-model="formData.tb">
                                            <label> Pananamlay</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="24" v-model="formData.tb">
                                            <label> Walang gana o mahinang kumain</label>
                                        </div>

                                    </div>

                                    <div class="flex flex-col"
                                        style="width: 10%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="25" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="26" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="27" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="28" v-model="formData.tb">
                                            <label> &gt;2 weeks</label>
                                        </div>

                                    </div>

                                    <div class="flex flex-col"
                                        style="width: 10%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="29" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="30" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="31" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="32" v-model="formData.tb">
                                            <label> &lt;2 weeks</label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col"
                                        style="width: 10%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="33" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="32" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="35" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="36" v-model="formData.tb">
                                            <label> Wala</label>
                                        </div>

                                    </div>
                                </div>

                                <!--  -->

                                <p class="mb-2 mt-4 font-bold">
                                    Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung mayroon ng alinman sa mga sumusunod na risk factors:
                                </p>

                                <div class="w-full flex flex-row text-sm">
                                    <div class="flex flex-col"
                                        style="width: 70%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="37" v-model="formData.tb">
                                            <label> Dating nag-gamutan sa sakit na TB</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="38" v-model="formData.tb">
                                            <label> May nakakasamang may sakit na TB sa matagal na panahon</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="39" v-model="formData.tb">
                                            <label> Diabetes | Cancer | Sumasailalim sa dialysis</label>
                                        </div>
                                    </div>



                                    <div class="flex flex-col"
                                        style="width: 30%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="40" v-model="formData.tb">
                                            <label> Edad 60 anyos pataas</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="41" v-model="formData.tb">
                                            <label> Indigent | 4P's/CCT Member</label>
                                        </div>

                                        <div class="w-full">
                                            <input type="checkbox" value="42" v-model="formData.tb">
                                            <label> Naninigarilyo | Madalas uminom ng alak</label>
                                        </div>
                                    </div>
                                </div>

                                <p class="mb-2 mt-4 font-bold">
                                    Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung anong aksyon ang ginawa para sa kliyente:
                                </p>

                                <div class="w-full flex flex-row text-sm mb-8">
                                    <div class="flex flex-col"
                                        style="width: 70%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="43" v-model="formData.tb">
                                            <label> Ni-refer sa health center dahil mayroon ng alinman sintomas o risk factor</label>
                                        </div>
                                    </div>



                                    <div class="flex flex-col"
                                        style="width: 30%"
                                    >
                                        <div class="w-full">
                                            <input type="checkbox" value="44" v-model="formData.tb">
                                            <label> Hindi ini-refer sa health center</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf>

                    <!-- TB Form -->
                    <div class="w-full flex-col" v-if="activeForm == 'Tuberculosis Symptom Form'">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-4" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('tb-dots')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        

                        <div class="w-full p-4"
                            style="height: auto; border: 1px solid black; border-radius: 5px"
                            id="tb-dots"
                        >
                            <div class="w-full text-2xl font-bold mb-5">
                                <span>
                                    {{ patient.name }} - {{ activeForm }}
                                </span>

                                <span class="float-right mr-4">
                                    {{ selectedDate }}
                                </span>
                            </div>

                            <p class="mb-2 font-bold">
                                Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung mayroon ng alinman sa mga sumusunod na sintomas at angkop na tagal ng sintomas na nararanasan:
                            </p>

                            <div class="w-full flex flex-row text-sm">
                                <div class="flex flex-col"
                                    style="width: 70%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="1" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Ubo</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="2" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Pag-ubo na may kasamang dugo</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="3" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Lagnat na di maipaliwanag ang sanhi</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="4" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Pamamayat na di maipaliwanag ang sanhi</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="5" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Labis na pagpapawis sa hapon o gabi na walang kinalaman sa init ng panahon</label>
                                    </div>
                                </div>

                                <div class="flex flex-col"
                                    style="width: 10%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="6" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="7" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="8" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="9" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="10" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                </div>

                                <div class="flex flex-col"
                                    style="width: 10%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="11" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="12" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="13" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="14" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="15" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>
                                </div>

                                <div class="flex flex-col"
                                    style="width: 10%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="16" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="17" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="18" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="19" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="20" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    
                                </div>
                            </div>

                            <!--  -->

                            <p class="mb-2 mt-4 font-bold">
                                Karagdagang tanong para sa mga batang edad 0-14:
                            </p>

                            <div class="w-full flex flex-row text-sm">
                                <div class="flex flex-col"
                                    style="width: 70%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="21" v-model="formData.tb" :disabled="options.isReport"> 
                                        <label> Ubo o kahirapan sa paghinga</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="22" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Mabilis mapagod</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="23" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Pananamlay</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="24" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Walang gana o mahinang kumain</label>
                                    </div>

                                </div>

                                <div class="flex flex-col" 
                                    style="width: 10%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="25" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="26" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="27" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="28" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &gt;2 weeks</label>
                                    </div>

                                </div>

                                <div class="flex flex-col"
                                    style="width: 10%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="29" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="30" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="31" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="32" v-model="formData.tb" :disabled="options.isReport">
                                        <label> &lt;2 weeks</label>
                                    </div>
                                </div>

                                <div class="flex flex-col"
                                    style="width: 10%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="33" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="34" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="35" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="36" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Wala</label>
                                    </div>

                                </div>
                            </div>

                            <!--  -->

                            <p class="mb-2 mt-4 font-bold">
                                Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung mayroon ng alinman sa mga sumusunod na risk factors:
                            </p>

                            <div class="w-full flex flex-row text-sm">
                                <div class="flex flex-col"
                                    style="width: 70%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="37" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Dating nag-gamutan sa sakit na TB</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="38" v-model="formData.tb" :disabled="options.isReport">
                                        <label> May nakakasamang may sakit na TB sa matagal na panahon</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="39" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Diabetes | Cancer | Sumasailalim sa dialysis</label>
                                    </div>
                                </div>



                                <div class="flex flex-col"
                                    style="width: 30%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="40" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Edad 60 anyos pataas</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="41" v-model="formData.tb" :disabled="options.isReport"> 
                                        <label> Indigent | 4P's/CCT Member</label>
                                    </div>

                                    <div class="w-full">
                                        <input type="checkbox" value="42" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Naninigarilyo | Madalas uminom ng alak</label>
                                    </div>
                                </div>
                            </div>

                            <p class="mb-2 mt-4 font-bold">
                                Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung anong aksyon ang ginawa para sa kliyente:
                            </p>

                            <div class="w-full flex flex-row text-sm mb-8">
                                <div class="flex flex-col"
                                    style="width: 70%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="43" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Ni-refer sa health center dahil mayroon ng alinman sintomas o risk factor</label>
                                    </div>
                                </div>



                                <div class="flex flex-col"
                                    style="width: 30%"
                                >
                                    <div class="w-full">
                                        <input type="checkbox" value="44" v-model="formData.tb" :disabled="options.isReport">
                                        <label> Hindi ini-refer sa health center</label>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full mb-2">
                                <button class="text-white" style="padding: 5px 20px 5px 20px; background: #003865; border-radius: 5px"
                                    @click="generateForm()"
                                    :class="{ 'cursor-not-allowed': formData.tb.length == 0, 'cursor-pointer': formData.tb.length > 0 }"
                                    :disabled="formData.tb.length == 0"
                                    v-if="!options.isReport"
                                >
                                    Submit
                                </button>
                            </div>

                            <!--  -->
                            
                        </div>
                    </div>
                    <!-- End of TB Form -->

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="1000"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="pregnancy"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div class="w-full text-2xl font-bold mb-5 ml-4">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>

                                <div class="w-full mt-2 mb-5 px-4 flex flex-row"
                                    style="height: auto;"
                                >   
                                    <div class="w-full">
                                        <label class="text-xl font-bold"> Last Menstrual Period</label> <br>
                                        <input type="date" v-model="formData.lmp" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px">
                                    </div>

                                    <div class="w-full">
                                        <label class="text-xl font-bold"> Expected Date of Confinement</label> <br>
                                        <input type="date" v-model="formData.edc" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px">
                                    </div>

                                    <div class="w-full">
                                        <label class="text-xl font-bold"> Expected Date of Delivery</label> <br>
                                        <input type="date" v-model="formData.edd" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px">
                                    </div>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 

                    <!-- Pregnancy Form -->
                    <div class="w-full" v-if="activeForm == 'Pregnancy Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('pregnancy')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        <div class="w-full text-2xl font-bold mb-5 ml-4">
                            <span>
                                {{ patient.name }} - {{ activeForm }}
                            </span>

                            <span class="float-right mr-4">
                                {{ selectedDate }}
                            </span>
                        </div>

                        <div class="w-full mt-2 mb-5 px-4 flex flex-row"
                            style="height: auto;"
                        >   
                            <div class="w-full">
                                <label class="text-xl font-bold"> Last Menstrual Period</label> <br>
                                <input type="date" v-model="formData.lmp" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px" :disabled="options.isReport"> 
                            </div>

                            <div class="w-full">
                                <label class="text-xl font-bold"> Expected Date of Confinement</label> <br>
                                <input type="date" v-model="formData.edc" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px" :disabled="options.isReport">
                            </div>

                            <div class="w-full">
                                <label class="text-xl font-bold"> Expected Date of Delivery</label> <br>
                                <input type="date" v-model="formData.edd" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px" :disabled="options.isReport">
                            </div>
                        </div>

                        <div class="w-full mb-5 px-4">
                            <button class="text-white" style="padding: 5px 20px 5px 20px; background: #003865; border-radius: 5px"
                                @click="generateForm()"
                                :class="{ 'cursor-not-allowed': !formData.lmp || !formData.edc || !formData.edd, 'cursor-pointer': !!formData.lmp && !!formData.edc && !!formData.edd }"
                                :disabled="!formData.lmp || !formData.edc || !formData.edd"
                                v-if="!options.isReport"
                            >
                                Submit
                            </button>
                        </div>
                            
                    </div>

                    <!-- End of Pregnancy Form -->

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="2500"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a2"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="prenatal"
                    >
                        <section slot="pdf-content">
                            <div class="w-full flex-col p-1">
                                <div class="w-full text-2xl font-bold mb-5">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>

                                <div class="w-full">
                                    <label>Today's Date:</label><br>
                                    <input type="date" class="--input mt-1" v-model="prenatal.date_today"><br>
                                    <span class="text-xs text-red-500 pl-2">{{validationError('date_today', saveError)}} </span>
                                </div>

                                <div class="w-full mt-2 font-bold">
                                    PREGNANCY CARE INFORMATION
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full pr-2">
                                        <label>Provider Fullname:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.provider_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('provider_name', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>NPI #:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.npi">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('npi', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>PhilHealth ID # or Billing ID #:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.billing_id">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('billing_id', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Tax ID #:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.tax_id">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('tax_id', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full pr-2">
                                        <label>Provider Address:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.provider_address">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('provider_address', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>City:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.city">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('city', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Town:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.town">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('town', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>ZIP:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.zip">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('zip', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Provider Phone:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.provider_phone">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('provider_phone', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Provider Fax:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.provider_fax">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('provider_fax', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full mt-2 font-bold">
                                    MEMBER INFORMATION
                                </div>

                                <div class="w-full mt-2">
                                    <b>NOTE:</b> Verify the member’s eligibility on the secure Provider portal before rendering services.
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full pr-2">
                                        <label>Member Fullname:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.member_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('member_name', saveError)}} </span>
                                    </div>

                                    

                                    <div class="w-full pr-2">
                                        <label>DOB:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.dob">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Age:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Member Address:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.member_address">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('member_address', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full pr-2">
                                        <label>Phone:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.member_phone">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('member_phone', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>City:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.member_city">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('member_city', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Town:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.member_town">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('member_town', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>ZIP:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.member_zip">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('member_zip', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full pr-2">
                                        <label>LMP:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.lmp">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('lmp', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>G:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.g">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('g', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>P:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.p">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('p', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>EDD:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.edd">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('edd', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full pr-2">
                                        <label>Date of first prenatal visit:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.first_prenatal">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('first_prenatal', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date of most recent prenatal visit:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.recent_prenatal">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('recent_prenatal', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Type of last delivery:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.type_of_last_delivery">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('type_of_last_delivery', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date of last delivery:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.last_delivery">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('last_delivery', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Expected delivery facility:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.delivery_facility">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('delivery_facility', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full mt-2 font-bold">
                                    RISK ASSESSMENT
                                </div>

                                <div class="w-full mt-2">
                                    <b>NOTE:</b> Check all applicable risks. You may attach your own risk assessment form if necessary.
                                </div>

                                <div class="w-full flex flex-row mt-2">
                                    <div class="w-full flex-col pr-2">
                                        <div class="w-full font-bold">
                                            Behavioral risks
                                        </div>

                                        <div class="w-full mt-2">
                                            <input type="checkbox" v-model="prenatal.behavioral_risks"  value="Smokes more than 10 cigarettes per day">
                                            <label style="transform: translateY(-10px);"> Smokes more than 10 cigarettes per day</label><br>
                                            

                                            <input type="checkbox" v-model="prenatal.behavioral_risks" value="Less than 2 years since last pregnancy">
                                            <label style="transform: translateY(-10px);"> Less than 2 years since last pregnancy</label><br>

                                            <input type="checkbox" v-model="prenatal.behavioral_risks" value="Directed member to WIC office">
                                            <label style="transform: translateY(-10px);"> Directed member to WIC office</label><br>

                                            <input type="checkbox" v-model="prenatal.behavioral_risks" value="Offered HIV counseling">
                                            <label style="transform: translateY(-10px);"> Offered HIV counseling</label><br>

                                            <input type="checkbox" v-model="prenatal.behavioral_risks" value="Substance use in pregnancy">
                                            <label style="transform: translateY(-10px);"> Substance use in pregnancy</label><br>
                                        </div>
                                    </div>

                                    <div class="w-full flex-col pr-2">
                                        <div class="w-full font-bold">
                                            Psychological risks
                                        </div>

                                        <div class="w-full mt-2">
                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Adolescent">
                                            <label style="transform: translateY(-10px);">Adolescent  </label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate finances">
                                            <label style="transform: translateY(-10px);">Inadequate finances </label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate housing">
                                            <label style="transform: translateY(-10px);">Inadequate housing</label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate social supports">
                                            <label style="transform: translateY(-10px);">Inadequate social supports</label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Involvement with other agencies, e.g., DCF">
                                            <label style="transform: translateY(-10px);">Involvement with other agencies, e.g., DCF</label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Less than high school education">
                                            <label style="transform: translateY(-10px);">Less than high school education</label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Poor nutrition">
                                            <label style="transform: translateY(-10px);">Poor nutrition</label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Psychiatric history">
                                            <label style="transform: translateY(-10px);">Psychiatric history</label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Significant learning disabilities">
                                            <label style="transform: translateY(-10px);">Significant learning disabilities </label><br>

                                            <input type="checkbox" v-model="prenatal.pyschological_risks" value="Violence/abuse">
                                            <label style="transform: translateY(-10px);">Violence/abuse</label><br>

                                        </div>
                                    </div>

                                    <div class="w-full flex-col pr-2">
                                        <div class="w-full font-bold">
                                            Medical risks
                                        </div>

                                        <div class="w-full mt-2">
                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Cardiac disease">
                                            <label style="transform: translateY(-10px);">Cardiac disease </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Diabetes">
                                            <label style="transform: translateY(-10px);">Diabetes </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Endocrine disorders">
                                            <label style="transform: translateY(-10px);">Endocrine disorders </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="GI disorders">
                                            <label style="transform: translateY(-10px);">GI disorders </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Hypothyroid">
                                            <label style="transform: translateY(-10px);">Hypothyroid </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Malignancy">
                                            <label style="transform: translateY(-10px);">Malignancy </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Moderate or severe asthma">
                                            <label style="transform: translateY(-10px);">Moderate or severe asthma</label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Renal disease/history of UTIs">
                                            <label style="transform: translateY(-10px);">Renal disease/history of UTIs </label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Seizure disorders">
                                            <label style="transform: translateY(-10px);"> Seizure disorders</label><br>

                                            <input type="checkbox" v-model="prenatal.medical_risks" value="Sickle cell disease">
                                            <label style="transform: translateY(-10px);"> Sickle cell disease</label><br>

                                        </div>
                                    </div>

                                    <div class="w-full flex-col pr-2">
                                        <div class="w-full font-bold">
                                            Obstetrics risks
                                        </div>

                                        <div class="w-full mt-2">
                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Abnormal pap smear">
                                            <label style="transform: translateY(-10px);">Abnormal pap smear </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Anemia">
                                            <label style="transform: translateY(-10px);">Anemia </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="BMI > 30">
                                            <label style="transform: translateY(-10px);">BMI > 30 </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Gestational diabetes">
                                            <label style="transform: translateY(-10px);">Gestational diabetes </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of cervical uterine infections">
                                            <label style="transform: translateY(-10px);">History of cervical uterine infections </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of infant with brain injury, neurological defect or congenial abnormality">
                                            <label style="transform: translateY(-10px);">
                                                History of infant with brain injury, neurological defect or congenial abnormality
                                            </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of infertility">
                                            <label style="transform: translateY(-10px);">History of infertility </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Inadequate prenatal care">
                                            <label style="transform: translateY(-10px);">Inadequate prenatal care </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of low birth weight infant">
                                            <label style="transform: translateY(-10px);">History of low birth weight infant </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Incompetent cervix">
                                            <label style="transform: translateY(-10px);">Incompetent cervix </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Intrauterine growth retardation (IUGR)">
                                            <label style="transform: translateY(-10px);">Intrauterine growth retardation (IUGR) </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Placenta Previa/Placenta Abruptia">
                                            <label style="transform: translateY(-10px);">Placenta Previa/Placenta Abruptia </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Polyhydramnios/oligohydramnios">
                                            <label style="transform: translateY(-10px);">Polyhydramnios/oligohydramnios </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Poor weight gain">
                                            <label style="transform: translateY(-10px);">Poor weight gain </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Postpartum depression – family history">
                                            <label style="transform: translateY(-10px);">Postpartum depression – family history </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Postpartum depression – personal history">
                                            <label style="transform: translateY(-10px);">Postpartum depression – personal history </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Pregnancy-induced hypertension (PIH)">
                                            <label style="transform: translateY(-10px);">Pregnancy-induced hypertension (PIH) </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Pre-pregnancy weight of less than 100 lbs.">
                                            <label style="transform: translateY(-10px);">Pre-pregnancy weight of less than 100 lbs. </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous pre-term labor (PTL)">
                                            <label style="transform: translateY(-10px);">Previous pre-term labor (PTL) </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous pre-term delivery (PTD)">
                                            <label style="transform: translateY(-10px);">Previous pre-term delivery (PTD) </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous stillborn/neonatal death">
                                            <label style="transform: translateY(-10px);">Previous stillborn/neonatal death </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Multiple gestation">
                                            <label style="transform: translateY(-10px);">Multiple gestation </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Rh sensitization">
                                            <label style="transform: translateY(-10px);">Rh sensitization </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Sexually transmitted disease (STD)">
                                            <label style="transform: translateY(-10px);">Sexually transmitted disease (STD) </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="2 or more spontaneous abortions (SABs) or therapeutic abortions (TABs)">
                                            <label style="transform: translateY(-10px);">
                                                2 or more spontaneous abortions (SABs) or therapeutic abortions (TABs)
                                            </label><br>

                                            <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Uterine/cervical anomaly">
                                            <label style="transform: translateY(-10px);">
                                                Uterine/cervical anomaly
                                            </label><br>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row mt-4">
                                    <div class="w-full pr-2">
                                        <label>OB/GYN provider signature:</label><br>
                                        <input type="text" class="--input w-full mt-1" v-model="prenatal.ob_gyn">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('ob_gyn', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date:</label><br>
                                        <input type="date" class="--input w-full mt-1" v-model="prenatal.date">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('date', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <br>
                                        <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                            @click="createPrenatal()"
                                            v-if="!options.isReport"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </section>
                    </VueHtml2pdf> 

                    <!-- Prenatal Registration Form -->
                    <div class="w-full" v-if="activeForm == 'Prenatal Registration Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-4" 
                                @click="activeForm = null"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('prenatal')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>
                        
                        <div class="w-full flex-col p-5">
                            <div class="w-full text-2xl font-bold mb-5">
                                <span>
                                    {{ patient.name }} - {{ activeForm }}
                                </span>

                                <span class="float-right mr-4">
                                    {{ selectedDate }}
                                </span>
                            </div>

                            <div class="w-full">
                                <label>Today's Date:</label><br>
                                <input type="date" class="--input" v-model="prenatal.date_today" :disabled="options.isReport"><br>
                                <span class="text-xs text-red-500 pl-2">{{validationError('date_today', saveError)}} </span>
                            </div>

                            <div class="w-full mt-10 font-bold">
                                PREGNANCY CARE INFORMATION
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Provider Fullname: (First Name Middle Name Last Name)</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_name" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_name', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>NPI #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.npi" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('npi', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>PhilHealth ID # or Billing ID #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.billing_id" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('billing_id', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Tax ID #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.tax_id" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('tax_id', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Provider Address:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_address" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_address', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>City:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.city" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('city', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Town:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.town" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('town', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>ZIP:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.zip" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('zip', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Provider Phone:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_phone" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_phone', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Provider Fax:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_fax" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_fax', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full mt-10 font-bold">
                                MEMBER INFORMATION
                            </div>

                            <div class="w-full mt-4">
                                <b>NOTE:</b> Verify the member’s eligibility on the secure Provider portal before rendering services.
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Member Fullname:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_name" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_name', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>DOB:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.dob" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Age:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.age" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Member Address:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_address" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_address', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Phone:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_phone" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_phone', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>City:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_city" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_city', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Town:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_town" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_town', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>ZIP:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_zip" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_zip', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>LMP:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.lmp" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('lmp', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>G:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.g" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('g', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>P:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.p" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('p', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>EDD:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.edd" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('edd', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Date of first prenatal visit:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.first_prenatal" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('first_prenatal', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Date of most recent prenatal visit:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.recent_prenatal" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('recent_prenatal', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Type of last delivery:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.type_of_last_delivery" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('type_of_last_delivery', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Date of last delivery:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.last_delivery" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('last_delivery', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Expected delivery facility:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.delivery_facility" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('delivery_facility', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full mt-10 font-bold">
                                RISK ASSESSMENT
                            </div>

                            <div class="w-full mt-4">
                                <b>NOTE:</b> Check all applicable risks. You may attach your own risk assessment form if necessary.
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Behavioral risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.behavioral_risks"  value="Smokes more than 10 cigarettes per day" :disabled="options.isReport">
                                        <label> Smokes more than 10 cigarettes per day</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Less than 2 years since last pregnancy" :disabled="options.isReport">
                                        <label> Less than 2 years since last pregnancy</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Directed member to WIC office" :disabled="options.isReport">
                                        <label> Directed member to WIC office</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Offered HIV counseling" :disabled="options.isReport">
                                        <label> Offered HIV counseling</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Substance use in pregnancy" :disabled="options.isReport">
                                        <label> Substance use in pregnancy</label><br>
                                    </div>
                                </div>

                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Psychological risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Adolescent" :disabled="options.isReport">
                                        <label>Adolescent  </label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate finances" :disabled="options.isReport">
                                        <label>Inadequate finances </label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate housing" :disabled="options.isReport">
                                        <label>Inadequate housing</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate social supports" :disabled="options.isReport">
                                        <label>Inadequate social supports</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Involvement with other agencies, e.g., DCF" :disabled="options.isReport">
                                        <label>Involvement with other agencies, e.g., DCF</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Less than high school education" :disabled="options.isReport">
                                        <label>Less than high school education</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Poor nutrition" :disabled="options.isReport">
                                        <label>Poor nutrition</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Psychiatric history" :disabled="options.isReport">
                                        <label>Psychiatric history</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Significant learning disabilities" :disabled="options.isReport">
                                        <label>Significant learning disabilities </label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Violence/abuse" :disabled="options.isReport">
                                        <label>Violence/abuse</label><br>

                                    </div>
                                </div>

                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Medical risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Cardiac disease" :disabled="options.isReport">
                                        <label>Cardiac disease </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Diabetes" :disabled="options.isReport">
                                        <label>Diabetes </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Endocrine disorders" :disabled="options.isReport">
                                        <label>Endocrine disorders </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="GI disorders" :disabled="options.isReport">
                                        <label>GI disorders </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Hypothyroid" :disabled="options.isReport">
                                        <label>Hypothyroid </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Malignancy" :disabled="options.isReport">
                                        <label>Malignancy </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Moderate or severe asthma" :disabled="options.isReport">
                                        <label>Moderate or severe asthma</label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Renal disease/history of UTIs" :disabled="options.isReport">
                                        <label>Renal disease/history of UTIs </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Seizure disorders" :disabled="options.isReport">
                                        <label> Seizure disorders</label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Sickle cell disease" :disabled="options.isReport">
                                        <label> Sickle cell disease</label><br>

                                    </div>
                                </div>

                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Obstetrics risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Abnormal pap smear" :disabled="options.isReport">
                                        <label>Abnormal pap smear </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Anemia" :disabled="options.isReport">
                                        <label>Anemia </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="BMI > 30" :disabled="options.isReport">
                                        <label>BMI > 30 </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Gestational diabetes" :disabled="options.isReport">
                                        <label>Gestational diabetes </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of cervical uterine infections" :disabled="options.isReport">
                                        <label>History of cervical uterine infections </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of infant with brain injury, neurological defect or congenial abnormality" :disabled="options.isReport">
                                        <label>
                                            History of infant with brain injury, neurological defect or congenial abnormality
                                        </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of infertility" :disabled="options.isReport">
                                        <label>History of infertility </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Inadequate prenatal care" :disabled="options.isReport">
                                        <label>Inadequate prenatal care </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of low birth weight infant" :disabled="options.isReport">
                                        <label>History of low birth weight infant </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Incompetent cervix" :disabled="options.isReport">
                                        <label>Incompetent cervix </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Intrauterine growth retardation (IUGR)" :disabled="options.isReport">
                                        <label>Intrauterine growth retardation (IUGR) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Placenta Previa/Placenta Abruptia" :disabled="options.isReport">
                                        <label>Placenta Previa/Placenta Abruptia </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Polyhydramnios/oligohydramnios" :disabled="options.isReport">
                                        <label>Polyhydramnios/oligohydramnios </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Poor weight gain" :disabled="options.isReport">
                                        <label>Poor weight gain </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Postpartum depression – family history" :disabled="options.isReport">
                                        <label>Postpartum depression – family history </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Postpartum depression – personal history" :disabled="options.isReport">
                                        <label>Postpartum depression – personal history </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Pregnancy-induced hypertension (PIH)" :disabled="options.isReport">
                                        <label>Pregnancy-induced hypertension (PIH) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Pre-pregnancy weight of less than 100 lbs." :disabled="options.isReport">
                                        <label>Pre-pregnancy weight of less than 100 lbs. </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous pre-term labor (PTL)" :disabled="options.isReport">
                                        <label>Previous pre-term labor (PTL) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous pre-term delivery (PTD)" :disabled="options.isReport">
                                        <label>Previous pre-term delivery (PTD) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous stillborn/neonatal death" :disabled="options.isReport">
                                        <label>Previous stillborn/neonatal death </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Multiple gestation" :disabled="options.isReport">
                                        <label>Multiple gestation </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Rh sensitization" :disabled="options.isReport">
                                        <label>Rh sensitization </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Sexually transmitted disease (STD)" :disabled="options.isReport">
                                        <label>Sexually transmitted disease (STD) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="2 or more spontaneous abortions (SABs) or therapeutic abortions (TABs)" :disabled="options.isReport">
                                        <label>
                                            2 or more spontaneous abortions (SABs) or therapeutic abortions (TABs)
                                        </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Uterine/cervical anomaly" :disabled="options.isReport">
                                        <label>
                                            Uterine/cervical anomaly
                                        </label><br>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-10">
                                <div class="w-full pr-2">
                                    <label>OB/GYN provider signature:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.ob_gyn" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('ob_gyn', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Date:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.date" :disabled="options.isReport">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <br>
                                    <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                        @click="createPrenatal()"
                                        v-if="!options.isReport"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="1000"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="nutrition"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div class="w-full text-2xl font-bold mb-5 ml-4">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Gurdian Fullname:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="nutrition.guardian_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date of Birth:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="nutrition.dob">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Age:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="nutrition.age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Height:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="nutrition.height">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                                    </div>

                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Weight:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="nutrition.weight">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>BMI:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="nutrition.bmi">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Vitamins:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="nutrition.vitamins">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('vitamins', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2 mt-5">
                                        <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                            @click="createNutritionForm()"
                                            v-if="!options.isReport"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 

                    <!-- Nutrition FOrm -->
                    <div class="w-full flex-col" v-if="activeForm == 'Nutrition Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('nutrition')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        <div class="w-full text-2xl font-bold mb-5 ml-4">
                            <span>
                                {{ patient.name }} - {{ activeForm }}
                            </span>

                            <span class="float-right mr-4">
                                {{ selectedDate }}
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Guardian Fullname:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.guardian_name" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Birth:</label><br>
                                <input type="date" class="--input w-full" v-model="nutrition.dob" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.age" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Height:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.height" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                            </div>

                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.weight" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>BMI:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.bmi" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Vitamins:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.vitamins" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('vitamins', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createNutritionForm()"
                                    v-if="!options.isReport"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="1000"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="deworming"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div class="w-full text-2xl font-bold mb-5 ml-4">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>
                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Guardian Fullname:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="deworming.guardian_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date of Birth:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="deworming.dob">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Age:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="deworming.age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Height:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="deworming.height">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                                    </div>

                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Weight:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="deworming.weight">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>BMI:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="deworming.bmi">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Deworming Medication:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="deworming.deworming_medication">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('deworming_medication', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2 mt-5">
                                        <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                            @click="createDewormingForm()"
                                            v-if="!options.isReport"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 

                    <!-- Deworming FOrm -->
                    <div class="w-full flex-col" v-if="activeForm == 'Deworming Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('deworming')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        <div class="w-full text-2xl font-bold mb-5 ml-4">
                            <span>
                                {{ patient.name }} - {{ activeForm }}
                            </span>

                            <span class="float-right mr-4">
                                {{ selectedDate }}
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Guardian Fullname:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.guardian_name" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Birth:</label><br>
                                <input type="date" class="--input w-full" v-model="deworming.dob" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.age" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Height:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.height" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                            </div>

                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.weight" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>BMI:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.bmi" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Deworming Medication:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.deworming_medication" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('deworming_medication', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createDewormingForm()"
                                    v-if="!options.isReport"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>


                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="1000"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="vaccination"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div class="w-full text-2xl font-bold mb-5 ml-4">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Guardian Fullname:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="vaccination.guardian_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date of Birth:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="vaccination.dob">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Age:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="vaccination.age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Height:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="vaccination.height">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                                    </div>

                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Weight:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="vaccination.weight">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>BMI:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="vaccination.bmi">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Vaccine:</label><br>
                                        <select v-model="vaccination.vaccination_id" class="--input w-full mt-2">
                                            <option v-for="vaccine in options.vaccinations" :value="vaccine.id" :key="vaccine.id">{{vaccine.name}}</option>
                                        </select>
                                        <span class="text-xs text-red-500 pl-2">{{validationError('vaccination_id', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2 mt-5">
                                        <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                            @click="createVaccinationForm()"
                                            v-if="!options.isReport"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 

                    <!-- Vaccination FOrm -->
                    <div class="w-full flex-col" v-if="activeForm == 'Vaccination Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('vaccination')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        <div class="w-full text-2xl font-bold mb-5 ml-4">
                            <span>
                                {{ patient.name }} - {{ activeForm }}
                            </span>

                            <span class="float-right mr-4">
                                {{ selectedDate }}
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Guardian Fullname:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.guardian_name" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Birth:</label><br>
                                <input type="date" class="--input w-full" v-model="vaccination.dob" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.age" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Height:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.height" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                            </div>

                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.weight" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>BMI:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.bmi" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Vaccine:</label><br>
                                <select v-model="vaccination.vaccination_id" class="--input w-full" :disabled="options.isReport">
                                    <option v-for="vaccine in options.vaccinations" :value="vaccine.id" :key="vaccine.id">{{vaccine.name}}</option>
                                </select>
                                <span class="text-xs text-red-500 pl-2">{{validationError('vaccination_id', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createVaccinationForm()"
                                    v-if="!options.isReport"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="true"
                        :preview-modal="true"
                        :paginate-elements-by-height="1400"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a3"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="postnatal"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div class="w-full text-2xl font-bold mb-5 ml-4">
                                    <span>
                                        {{ patient.name }} - {{ activeForm }}
                                    </span>

                                    <span class="float-right mr-4">
                                        {{ selectedDate }}
                                    </span>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Hospital Name:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.hospital_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('hospital_name', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Client's Fullname:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.clients_name">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('clients_name', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Address:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.address">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('address', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Occupation:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.occupation">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('occupation', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Type of Delivery:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.type_of_delivery">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('type_of_delivery', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Mode of Delivery:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.mode_of_delivery">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('mode_of_delivery', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Total Hours of Labor:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.total_hours_of_labor">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('total_hours_of_labor', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Postnatal Day:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.postnatal_day">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('postnatal_day', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Registration #:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.register_no">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('register_no', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Date of Arrival:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="postnatal.doa">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('doa', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Religion:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.religion">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('religion', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Gestational Age:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.gestational_age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('gestational_age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Last Menstrual Period:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="postnatal.lmp">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('lmp', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>New Born Sex:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.new_born_sex">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('new_born_sex', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>DOB:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="postnatal.dob">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Age:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Education:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.education">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('education', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Obstetrical Score:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.obstetrical_score">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('obstetrical_score', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Chief Complain:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.chief_complain">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('chief_complain', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Estimated Date of Delivery:</label><br>
                                        <input type="date" class="--input w-full mt-2" v-model="postnatal.edd">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('edd', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>New Born Weight:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.newborn_weight">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('newborn_weight', saveError)}} </span>
                                    </div>


                                    <div class="w-full pr-2">
                                        <label>Elimination Pattern:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.elimination_pattern">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('elimination_pattern', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Activity and Exercise:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.activity_and_exercise">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('activity_and_exercise', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Menarche Age:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.menarche_age">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('menarche_age', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Period:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.period">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('period', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Bad Habits:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.bad_habits">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('bad_habits', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Nutritional Pattern:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.nutritional_pattern">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('nutritional_pattern', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Sleeping Pattern</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.sleeping_pattern">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('sleeping_pattern', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Cycle:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.cycle">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('cycle', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Amount of Blood Loss:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.amount_of_blood_loss">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('amount_of_blood_loss', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Duration of Marriage:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.duration_of_marriage">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('duration_of_marriage', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Family Planning Method Adopted:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.family_planning_method_adopted">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('family_planning_method_adopted', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Diseases</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.diseases">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('diseases', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Genitics Condition:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.genitics_condition">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('genitics_condition', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Parity:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.parity">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('parity', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Gravidity:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.gravidity">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('gravidity', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Miscarriages:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.miscarriages">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('miscarriages', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Terminations</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.terminations">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('terminations', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="w-full flex flex-row p-4">
                                    <div class="w-full pr-2">
                                        <label>Previous Pregnancy:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.previous_pregnancy">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('previous_pregnancy', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Length of Pregnancy:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.length_of_pregnancy">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('length_of_pregnancy', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2">
                                        <label>Induction:</label><br>
                                        <input type="text" class="--input w-full mt-2" v-model="postnatal.induction">
                                        <span class="text-xs text-red-500 pl-2">{{validationError('induction', saveError)}} </span>
                                    </div>

                                    <div class="w-full pr-2 mt-5">
                                        <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                            @click="createPostnatalForm()"
                                            v-if="!options.isReport"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 

                    <!-- Postnatal Registration Form -->
                    <div class="w-full flex-col" v-if="activeForm == 'Postnatal Registration Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full" v-if="!options.isReport">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" v-if="options.isReport" 
                                @click="printReport('postnatal')"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        <div class="w-full text-2xl font-bold mb-5 ml-4">
                            <span>
                                {{ patient.name }} - {{ activeForm }}
                            </span>

                            <span class="float-right mr-4">
                                {{ selectedDate }}
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Hospital Name:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.hospital_name" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('hospital_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Client's Fullname:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.clients_name" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('clients_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Address:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.address" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('address', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Occupation:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.occupation" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('occupation', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Type of Delivery:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.type_of_delivery" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('type_of_delivery', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Mode of Delivery:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.mode_of_delivery" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('mode_of_delivery', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Total Hours of Labor:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.total_hours_of_labor" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('total_hours_of_labor', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Postnatal Day:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.postnatal_day" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('postnatal_day', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Registration #:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.register_no" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('register_no', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Arrival:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.doa" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('doa', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Religion:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.religion" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('religion', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Gestational Age:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.gestational_age" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('gestational_age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Last Menstrual Period:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.lmp" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('lmp', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>New Born Sex:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.new_born_sex" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('new_born_sex', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>DOB:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.dob" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.age" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Education:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.education" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('education', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Obstetrical Score:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.obstetrical_score" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('obstetrical_score', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Chief Complain:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.chief_complain" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('chief_complain', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Estimated Date of Delivery:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.edd" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('edd', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>New Born Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.newborn_weight" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('newborn_weight', saveError)}} </span>
                            </div>


                            <div class="w-full pr-2">
                                <label>Elimination Pattern:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.elimination_pattern" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('elimination_pattern', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Activity and Exercise:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.activity_and_exercise" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('activity_and_exercise', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Menarche Age:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.menarche_age" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('menarche_age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Period:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.period" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('period', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Bad Habits:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.bad_habits" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bad_habits', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Nutritional Pattern:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.nutritional_pattern" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('nutritional_pattern', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Sleeping Pattern</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.sleeping_pattern" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('sleeping_pattern', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Cycle:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.cycle" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('cycle', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Amount of Blood Loss:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.amount_of_blood_loss" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('amount_of_blood_loss', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Duration of Marriage:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.duration_of_marriage" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('duration_of_marriage', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Family Planning Method Adopted:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.family_planning_method_adopted" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('family_planning_method_adopted', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Diseases</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.diseases" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('diseases', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Genitics Condition:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.genitics_condition" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('genitics_condition', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Parity:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.parity" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('parity', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Gravidity:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.gravidity" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('gravidity', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Miscarriages:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.miscarriages" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('miscarriages', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Terminations</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.terminations" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('terminations', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Previous Pregnancy:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.previous_pregnancy" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('previous_pregnancy', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Length of Pregnancy:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.length_of_pregnancy" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('length_of_pregnancy', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Induction:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.induction" :disabled="options.isReport">
                                <span class="text-xs text-red-500 pl-2">{{validationError('induction', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createPostnatalForm()"
                                    v-if="!options.isReport"
                                >
                                    Save
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </Navigation>
    </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import Navigation from "../Layouts/Sidebar";
import Table from "../Components/Table";
import * as htmlToImage from 'html-to-image';
import { toPng, toJpeg, toBlob, toPixelData, toSvg } from 'html-to-image';
import axios from "axios";
import VueHtml2pdf from 'vue-html2pdf'

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table,
        VueHtml2pdf
    },
    data(){
        return {
            patient: null,
            activeForm: null,
            formName: 'Tuberculosis Symptom Form',
            columns: [
                'Form', 'Date'
            ],
            keys : [
                {
                    label: 'name',
                },
                {
                    label: 'date_issued',
                },

            ],
            selectedForm: null,
            formData: {
                id: null,
                patient_id: null,
                description: null,
                name: null,
                tb: [],
                lmp: null,
                edc: null,
                edd: null
            },
            prenatal: {
                id: null,
                patient_id: null,
                date_today: null,
                provider_name: null,
                npi: null,
                billing_id: null,
                tax_id: null,
                provider_address: null,
                city: null,
                town: null,
                zip: null,
                provider_phone: null,
                provider_fax: null,
                member_name: null,
                age: null,
                dob: null,
                member_address: null,
                member_phone: null,
                member_city: null,
                member_town: null,
                member_zip: null,
                lmp: null,
                g: null,
                p: null,
                edd: null,
                first_prenatal: null,
                recent_prenatal: null,
                type_of_last_delivery: null,
                last_delivery: null,
                delivery_facility: null,
                behavioral_risks: [],
                pyschological_risks: [],
                medical_risks: [],
                obstetrics_risks: [],
                ob_gyn: null,
                date: null,
            },
            postnatal: {
                id: null,
                patient_id: null,
                hospital_name: null,
                clients_name: null,
                address: null,
                occupation: null,
                type_of_delivery: null,
                mode_of_delivery: null,
                total_hours_of_labor: null,
                postnatal_day: null,
                register_no: null,
                doa: null,
                religion: null,
                gestational_age: null,
                lmp: null,
                new_born_sex: null,
                dob: null,
                age: null,
                education: null,
                obstetrical_score: null,
                chief_complain: null,
                edd: null,
                newborn_weight: null,
                bad_habits: null,
                elimination_pattern: null,
                activity_and_exercise: null,
                menarche_age: null,
                period: null,
                nutritional_pattern: null,
                sleeping_pattern: null,
                cycle: null,
                amount_of_blood_loss: null,
                duration_of_marriage: null,
                family_planning_method_adopted: null,
                diseases: null,
                genitics_condition: null,
                gravidity: null,
                parity: null,
                miscarriages: null,
                terminations: null,
                previous_pregnancy: null,
                length_of_pregnancy: null,
                induction: null,
            },
            nutrition: {
                id: null,
                patient_id: null,
                guardian_name: null,
                dob: null,
                age: null,
                height: null,
                weight: null,
                bmi: null,
                vitamins: null,
            },
            deworming: {
                id: null,
                patient_id: null,
                guardian_name: null,
                dob: null,
                age: null,
                height: null,
                weight: null,
                bmi: null,
                deworming_medication: null,
            },
            vaccination: {
                id: null,
                patient_id: null,
                guardian_name: null,
                dob: null,
                age: null,
                height: null,
                weight: null, 
                bmi: null,
                vaccination_id: null,
            },
            checked: [],
            saveError: null,
            forms: [],
            activeID: null,
            selectedDate: null,
            startYear: null,
            endYear: null,
            years: [],
            selectedYear: null,
        }
    },

    created() {
        this.patient = this.options.patient

        this.formData.patient_id = this.patient.id

        this.formData.name = this.formName

        this.prenatal.patient_id = this.patient.id
        this.postnatal.patient_id = this.patient.id
        this.nutrition.patient_id = this.patient.id
        this.deworming.patient_id = this.patient.id
        this.vaccination.patient_id = this.patient.id

        var date = new Date();

        var currentDate = date.toISOString().slice(0,10);

        this.prenatal.date_today = currentDate

        this.startYear = 1800;
        this.endYear = new Date().getFullYear();

        for (var i = this.endYear; i > this.startYear; i--){
            // $('#yearpicker').append($('<option />').val(i).html(i));
            this.years.push(i)
        }

        this.selectedYear = this.years[0]

        if(!this.options.isReport){
            if(this.auth.user_type == 'doctor' || this.auth.user_type == 'leader' || this.auth.user_type == 'member') {
                this.forms = this.options.forms.filter(x => { return x.name == 'Tuberculosis Symptom Form' })
            } else {
                this.forms = this.options.forms.filter(x => { return x.name != 'Tuberculosis Symptom Form' })
            }
        } else {
            this.forms = this.options.forms.filter(x => {
                return x.display_year == this.selectedYear
            })
        }
    },

    watch: {
        activeForm(arg) {
            this.formData.name = arg
        },

        selectedYear(arg) {
            this.forms = this.options.forms.filter(x => {
                return x.display_year == this.selectedYear
            })
        },

        'formData.tb': {
            handler: function (val, oldVal) {
            },
            deep: true
        },

        selectedForm(arg) {
            this.selectedDate = arg.date_issued
            if(arg.name == 'Prenatal Registration Form') {
                this.prenatal = Object.assign({}, arg);
            }else if (arg.name == 'Postnatal Registration Form') {
                this.postnatal = Object.assign({}, arg);
            } else if(arg.name == 'Nutrition Form') {
                this.nutrition = Object.assign({}, arg);
            } else if(arg.name == 'Deworming Form') {
                this.deworming = Object.assign({}, arg);
                } else if(arg.name == 'Vaccination Form') {
                this.vaccination = Object.assign({}, arg);
            } else {
                this.formData.id = arg.id
                this.formData.name = arg.name
                this.formData.description = arg.description
                this.formData.patient_id = arg.patient_id
                this.formData.tb = arg.tb
                this.formData.lmp = arg.lmp
                this.formData.edc = arg.edc
                this.formData.edd = arg.edd
            }
            

            this.formName = arg.name
            this.activeForm = arg.name

        },
        'nutrition.dob'(arg){
            this.nutrition.age = this.calculateBirthday(arg)
        },

        'deworming.dob'(arg){
            this.deworming.age = this.calculateBirthday(arg)
        },

        'vaccination.dob'(arg){
            this.vaccination.age = this.calculateBirthday(arg)
        },

        'prenatal.dob'(arg){
            this.prenatal.age = this.calculateBirthday(arg)
        },

        'postnatal.dob'(arg){
            this.postnatal.age = this.calculateBirthday(arg)
        },
    },

    methods: {
        back(){
            var url = '/patients'

            if(this.options.isReport) {
                url = '/reports'
            }
            Inertia.get(
                this.$root.route + url,
                {
                    onSuccess: () => { },
                },
            );
        },

        generateForm(){
            this.formData.description = this.activeForm

            swal({
                title: "Are you sure to save this form?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/patients/create-patient/form', this.formData,
                    {
                        onSuccess: (res) => {
                            swal({
                                title: "Good job!",
                                text: "You successfuly save this form",
                                icon: "success",
                                button: "Okay",
                            });
                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                        }
                    });
                } 
            });

            
        },

        createPrenatal(){
            swal({
                title: "Are you sure to save this form?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/patients/create-patient/prenatal', this.prenatal,
                    {
                        onSuccess: (res) => {
                            swal({
                                title: "Good job!",
                                text: "You successfuly save this form",
                                icon: "success",
                                button: "Okay",
                            });
                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                        }
                    });
                } 
            });
        },
        createNutritionForm(){
            swal({
                title: "Are you sure to save this form?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/patients/create-patient/nutrition', this.nutrition,
                    {
                        onSuccess: (res) => {
                            swal({
                                title: "Good job!",
                                text: "You successfuly save this form",
                                icon: "success",
                                button: "Okay",
                            });
                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                        }
                    });
                } 
            });
        },
        createDewormingForm(){
            swal({
                title: "Are you sure to save this form?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/patients/create-patient/deworming', this.deworming,
                    {
                        onSuccess: (res) => {
                            swal({
                                title: "Good job!",
                                text: "You successfuly save this form",
                                icon: "success",
                                button: "Okay",
                            });
                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                        }
                    });
                } 
            });
        },

        createVaccinationForm() {
            swal({
                title: "Are you sure to save this form?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/patients/create-patient/vaccination', this.vaccination,
                    {
                        onSuccess: (res) => {
                            swal({
                                title: "Good job!",
                                text: "You successfuly save this form",
                                icon: "success",
                                button: "Okay",
                            });
                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                        }
                    });
                } 
            });
        },

        createPostnatalForm(){
            swal({
                title: "Are you sure to save this form?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/patients/create-patient/postnatal', this.postnatal,
                    {
                        onSuccess: (res) => {
                            swal({
                                title: "Good job!",
                                text: "You successfuly save this form",
                                icon: "success",
                                button: "Okay",
                            });
                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                        }
                    });
                } 
            });
        },
        printReport(arg){
            this.$refs[arg].generatePdf()
        },

        printProfile() {
            this.$refs.profile.generatePdf()
        },

        printHistory(){
            this.$refs.history.generatePdf()
        }
    }
}

</script>

<style scoped>
    .--input {
        height: 40px;
        border: 1px solid #366422;
        border-radius: 5px;
        padding: 5px 10px 5px 10px;
        
    }

    label {
        font-weight: bold;
    }

    .--main {

    }

    input[type=text] {
        text-transform: capitalize;
    }

    .--view__profile {
        background: #366422;
        border-radius: 5px;
        color: white;
        padding: 5px 15px 5px 15px;
        font-size: 0.750em;
    }

    .--input {
        width: 100%;
        height: 40px;
        border: 1px solid black;
        border-radius: 10px;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        border-radius: 5px;
        border-style: hidden;
        box-shadow: 0 0 0 1px black;
    }

    td {
        border: 1px solid black;
        padding-top: 5px;
        padding-bottom: 5px;
        color: black;
        font-size: 12px;
    }

    th {
        border: 1px solid black;
        color: black;
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>