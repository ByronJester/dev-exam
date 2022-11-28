<template>
	<div class="">
        <Navigation :auth="auth">
            <div class="px-4 pt-12" >
                <div class="flex flex-row mb-5" v-if="!newUser">
                    <div class="w-10/12">
                        <input type="text" class="--search pl-5"
                            v-model="form.search" @input="initiateSearch()"
                            placeholder="Search...."
                        >
                    </div>

                    <div class="w-2/12">
                        <button class="text-black float-right p-2"
                            style="border: 1px solid black; border-radius: 5px"
                            @click="openModal()"
                        >
                            <i class="fa-solid fa-person-circle-plus fa-2xl"></i>
                        </button>
                    </div>
                </div>

                <div class="flex flex-row" v-if="!newUser">
                    <div class="grid grid-cols-4 gap-2 w-full h-full"
                        :class="{'w-7/12': !!newUser}"
                    >
                        <div v-for="patient in patients" :key="patient.id" 
                            class="--user"
                            style="background-color: #EFDAD7"
                        >   
                            <div class="flex flex-row">
                                <div class="w-2/5 p-2">
                                    <img src="/images/dp.jpg"
                                        class="--display__picture"
                                    />
                                </div>

                                <div class="w-3/5">
                                    <div class="w-full text-center">
                                        <span class="text-md font-bold" 
                                            :class="{'text-lg' : !newUser}">{{ patient.name }}
                                        </span>
                                    </div>

                                    <div class="flex flex-row px-2 text-xs mt-2" :class="{'text-sm' : !newUser}">
                                        <div class="w-full font-bold">
                                            <p>
                                                Place : 
                                            </p>

                                            <p>
                                                Contact :
                                            </p>

                                            <p>
                                                Age :
                                            </p>

                                            <p>
                                                Gender :
                                            </p>
                                            
                                        </div>

                                        <div class="w-full">
                                            <p>
                                                {{ patient.place.name }} 
                                            </p>

                                            <p>
                                                {{ patient.phone }}
                                            </p>

                                            <p>
                                                {{ patient.age }}
                                            </p>

                                            <p>
                                                {{ patient.gender }}
                                            </p>
                                        </div>
                                    </div> 

                                    <div class="w-full inline-flex ml-2">
                                        <button class="--view__profile my-2 mr-1" @click="viewProfile(patient)" v-if="auth.user_type != 'pharmacist'">
                                            Forms
                                        </button>

                                        <button class="--view__profile my-2 mr-1" @click="viewMedicalHistory(patient)" v-if="auth.user_type != 'pharmacist'">
                                            Medical History
                                        </button>

                                        <button class="--view__profile my-2" @click="viewPersonalData(patient)" v-if="auth.user_type != 'pharmacist'">
                                           Profile
                                        </button>
                                    </div>

                                </div>
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
                    pdf-orientation="portrait"
                    pdf-content-width="100%"
                    ref="profile"
                >
                    <section slot="pdf-content">
                        <div class="w-full p-5">
                            <div class="w-full">
                                <span class="text-lg font-bold">
                                    {{ isPersonalData ? 'Profile' : 'New Patient' }}
                                </span>
                            </div>

                            <div class="w-full flex flex-col">
                                <div class="my-1">
                                    <label class="text-bold">Fullname: (First Name Middle Name Last Name)</label><br>
                                    <input type="text" class="--input mt-2" v-model="formData.name" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('name', saveError)}} </span>
                                </div>

                                <div class="my-1" v-if="auth.role != 3">
                                    <label for="cars">Barangay:</label><br>
                                    <select class="--input mt-2" v-model="formData.place_id" :disabled="isPersonalData">
                                        <option v-for="place in options.places" :key="place.id"
                                            :value="place.id"
                                        >
                                            {{ place.name }}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('place_id', saveError)}} </span>
                                </div>

                                <div class="my-1">
                                    <label class="text-bold">Contact No.:</label><br>
                                    <input type="text" class="--input mt-2" v-model="formData.phone" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('phone', saveError)}} </span>
                                </div>

                                <div class="w-full flex flex-row">
                                    <div class="my-1 mx-1 w-full">
                                        <label class="text-bold">Date of Birth:</label><br>
                                        <input type="date" class="--input mt-2" v-model="formData.dob" :disabled="isPersonalData">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('dob', saveError)}} </span>
                                    </div>

                                    <div class="my-1 mx-1 w-full">
                                        <label class="text-bold">Age:</label><br>
                                        <input type="text" class="--input mt-2" v-model="formData.age" :disabled="isPersonalData">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('age', saveError)}} </span>
                                    </div>
                                </div>
                                
                                <div class="w-full flex flex-row">
                                    <div class="my-1 mx-1 w-full">
                                        <label for="cars">Gender:</label><br>
                                        <select class="--input mt-2" v-model="formData.gender" :disabled="isPersonalData">
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
                                        <select class="--input mt-2" v-model="formData.civil_status" :disabled="isPersonalData">
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
                                        <input type="text" class="--input mt-2" v-model="formData.contact_person" :disabled="isPersonalData">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('contact_person', saveError)}} </span>
                                    </div>

                                    <div class="my-1 mx-1">
                                        <label class="text-bold">Contact Person Address:</label><br>
                                        <input type="text" class="--input mt-2" v-model="formData.contact_person_address" :disabled="isPersonalData">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('contact_person_address', saveError)}} </span>
                                    </div>

                                    <div class="my-1 mx-1">
                                        <label class="text-bold">Contact Person #:</label><br>
                                        <input type="text" class="--input mt-2" v-model="formData.contact_person_phone" :disabled="isPersonalData">
                                        <span class="text-xs text-red-500 ml-2">{{validationError('contact_person_phone', saveError)}} </span>
                                    </div>
                                </div>

                                <div class="my-1" v-if="auth.user_type == 'doctor'">
                                    <label class="text-bold">Diagnosis:</label><br>
                                    <input type="text" class="--input mt-2" v-model="formData.diagnosis" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('philhealth', saveError)}} </span>
                                </div>

                                <div class="my-1">
                                    <label class="text-bold">Philhealth ID #:</label><br>
                                    <input type="text" class="--input mt-2" v-model="formData.philhealth" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('philhealth', saveError)}} </span>
                                </div>
                            </div>
                        </div>
                    </section>
                </VueHtml2pdf> 

                <div id="patientModal" class="patientModal">
                    <div class="patient-modal-content flex flex-col" style="width: 30%">
                        <div class="w-full">
                            <span class="text-lg font-bold">
                                 {{ isPersonalData ? 'Profile' : 'New Patient' }}
                            </span>
                            <span class="float-right cursor-pointer"
                                @click="closeModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>

                            <span class="float-right cursor-pointer mr-5"
                                @click="printProfile()"
                                v-if="isPersonalData"
                            >
                                <i class="fa-solid fa-print"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="my-1">
                                <label class="text-bold">Fullname: (First Name Middle Name Last Name)</label><br>
                                <input type="text" class="--input" v-model="formData.name" :disabled="isPersonalData">
                                <span class="text-xs text-red-500 ml-2">{{validationError('name', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="auth.role != 3">
                                <label for="cars">Barangay:</label><br>
                                <select class="--input" v-model="formData.place_id" :disabled="isPersonalData">
                                    <option v-for="place in options.places" :key="place.id"
                                        :value="place.id"
                                    >
                                        {{ place.name }}
                                    </option>
                                </select>
                                <span class="text-xs text-red-500 ml-2">{{validationError('place_id', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Contact No.:</label><br>
                                <input type="text" class="--input" v-model="formData.phone" :disabled="isPersonalData">
                                <span class="text-xs text-red-500 ml-2">{{validationError('phone', saveError)}} </span>
                            </div>

                            <div class="w-full flex flex-row">
                                <div class="my-1 mx-1 w-full">
                                    <label class="text-bold">Date of Birth:</label><br>
                                    <input type="date" class="--input" v-model="formData.dob" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dob', saveError)}} </span>
                                </div>

                                <div class="my-1 mx-1 w-full">
                                    <label class="text-bold">Age:</label><br>
                                    <input type="text" class="--input" v-model="formData.age" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('age', saveError)}} </span>
                                </div>
                            </div>
                            
                            <div class="w-full flex flex-row">
                                <div class="my-1 mx-1 w-full">
                                    <label for="cars">Gender:</label><br>
                                    <select class="--input" v-model="formData.gender" :disabled="isPersonalData">
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
                                    <select class="--input" v-model="formData.civil_status" :disabled="isPersonalData">
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
                                    <input type="text" class="--input" v-model="formData.contact_person" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('contact_person', saveError)}} </span>
                                </div>

                                <div class="my-1 mx-1">
                                    <label class="text-bold">Contact Person Address:</label><br>
                                    <input type="text" class="--input" v-model="formData.contact_person_address" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('contact_person_address', saveError)}} </span>
                                </div>

                                <div class="my-1 mx-1">
                                    <label class="text-bold">Contact Person #:</label><br>
                                    <input type="text" class="--input" v-model="formData.contact_person_phone" :disabled="isPersonalData">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('contact_person_phone', saveError)}} </span>
                                </div>
                            </div>

                            <div class="my-1" v-if="auth.user_type == 'doctor'">
                                <label class="text-bold">Diagnosis:</label><br>
                                <input type="text" class="--input mt-2" v-model="formData.diagnosis" :disabled="isPersonalData">
                                <span class="text-xs text-red-500 ml-2">{{validationError('philhealth', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Philhealth ID #:</label><br>
                                <input type="text" class="--input" v-model="formData.philhealth" :disabled="isPersonalData">
                                <span class="text-xs text-red-500 ml-2">{{validationError('philhealth', saveError)}} </span>
                            </div>


                            <div class="mt-3 mb-2">
                                <button class="w-full py-2 px-4 text-white font-bold" 
                                    style="border-radius: 50px; background-color: #4D77FF"
                                    @click="createUser()"
                                    v-if="!isPersonalData"
                                >
                                    Create New Patient
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
import VueHtml2pdf from 'vue-html2pdf'

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        VueHtml2pdf
    },

    data(){
        return {
            patients: [],
            form: {
                search: null
            },
            newUser: false,
            selected: null,
            userType: [],
            formData: {
                place_id: null,
                name : null,
                phone : null,
                dob: null,
                age: null,
                gender: 'Male',
                civil_status: 'Single',
                philhealth: null,
                contact_person: null,
                contact_person_address: null,
                contact_person_phone: null,
                diagnosis: null
            },
            saveError: null,
            isPersonalData: false
        }
    },

    mounted(){
        this.patients = this.options.patients
        this.form.search = this.options.search

        if(this.auth != 3) {
            this.formData.place_id = this.auth.work_address
        }
    },

    methods: {

        initiateSearch() {
            var self = this

            self.newUser = false;
            self.selected = null;

            clearTimeout(self.timeOut);

            this.timeOut = setTimeout(
                function(){
                    self.search()
                }
            , 2000);
        },

        search() {
            Inertia.get( 
                this.$root.route + '/patients', this.form,
                {
                    onSuccess: () => { },
                },
            );
        },

        viewProfile(arg) {
            this.selected = arg

            Inertia.get(
                this.$root.route + '/patients/' + arg.id + '/' + false,
                {
                    onSuccess: () => { },
                },
            );
        },

        viewMedicalHistory(arg) {
            Inertia.get(
                this.$root.route + '/history/' + arg.id,
                {
                    onSuccess: () => { },
                },
            );
        },

        viewPersonalData(arg){
            this.formData = Object.assign({}, arg)

            this.isPersonalData = true

            this.openModal()
        },

        viewMedicine(arg){
            this.selected = arg

            Inertia.get(
                this.$root.route + '/patients/medicine/' + arg.id,
                {
                    onSuccess: () => { },
                },
            );
        },

        createUser(){
            if(this.auth.role == 3) {
                this.formData.place_id = this.auth.work_address
            }

            Inertia.post(this.$root.route + '/patients/create-patient', this.formData,
            {
                onSuccess: (res) => {
                    this.formData = {
                        place_id : null,
                        name : null,
                        phone : null,
                        age: null,
                        gender: 'Male'
                    }

                    location.reload()
                },
                onError: (err) => {
                    this.saveError = err
                }
            });
        },
        openModal(){
            var modal = document.getElementById("patientModal");

            modal.style.display = "block";
            
            this.newUser = true
        },

        closeModal(){
            var modal = document.getElementById("patientModal");

            modal.style.display = "none";

            this.newUser = false

            this.formData = {
                place_id : null,
                name : null,
                phone : null,
                age: null,
                gender: 'Male'
            }

            this.isPersonalData = false
                

            this.saveError = null
        },

        printProfile() {
            this.$refs.profile.generatePdf()
        }
    }
}

</script>

<style scoped>
.--user {
    border: 1px solid #22577E;
    border-radius: 5px;
}

.--view__profile {
    background: #366422;
    border-radius: 5px;
    color: white;
    padding: 5px 15px 5px 15px;
    font-size: 12px;
}

.--display__picture {
    width: 100%; 
    height: 150px; 
    border: 2px solid black; 
    border-radius: 20px;
}

.--search {
    width: 20%;
    height: 40px;
    border: 1px solid black;
    border-radius: 40px;
}

.--input {
    width: 100%;
    height: 40px;
    border: 1px solid black;
    border-radius: 10px;
    text-align: center;
}

.--view--display__picture {
    width: 100px; 
    height: 100px; 
    border: 2px solid black; 
    border-radius: 10px;
}

.patientModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.patient-modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 100%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

</style>