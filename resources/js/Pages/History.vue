<template>
    <div class="w-full h-screen">
        <Navigation :auth="auth">
            <div class="w-full flex flex-col">
                <div class="w-full flex flex-row mt-8" style="height: 5vh;">
                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        @click="activeTab = 'personal'"
                        :class="{'--bg_gray': activeTab == 'personal'}"
                    >
                        Personal Medical History
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        :class="{'--bg_gray': activeTab == 'family'}"
                        @click="activeTab = 'family'"
                    >
                        Family Medical History
                    </div>
                </div>

                <div class="w-full flex flex-col p-4" v-if="activeTab == 'personal'">
                    <div class="w-full flex flex-col">
                        <p class="text-2xl">
                            ALLERGIES
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Allergy:</label><br>
                                    <input type="text" v-model="formAllergy.allergy" placeholder="Allergy" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('allergy', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Allergic Reaction:</label><br>
                                    <input type="text" v-model="formAllergy.allergic_reaction" placeholder="Allergic Reaction" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('allergic_reaction', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveAllergy()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['ALLERGY', 'ALLERGIC REACTION']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.allergies" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'allergy'}, {label: 'allergic_reaction'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.allergies[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10">
                                MEDICATIONS
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Medications:</label><br>
                                    <input type="text" v-model="formMedication.medications" placeholder="Medications" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicaions', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Dose:</label><br>
                                    <input type="text" v-model="formMedication.dose" placeholder="Dose" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dose', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Times Per Day:</label><br>
                                    <input type="text" v-model="formMedication.times_per_day" placeholder="Times Per Day" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('times_per_day', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveMedication()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['MEDICATIONS', 'DOSE', 'TIMES PER DAY']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.medications" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'medications'}, {label: 'dose'}, {label: 'times_per_day'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.medications[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>


                        <p class="text-2xl mt-10">
                                HEALTH MAINTENANCE HISTORY
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <select class="--input" v-model="formMaintenance.type">
                                        <option :value="'CHOLESTEROL'">
                                            CHOLESTEROL
                                        </option>

                                        <option :value="'COLONOSCOPY/SIGMOID'">
                                            COLONOSCOPY/SIGMOID
                                        </option>
                                        
                                        <option :value="'MAMMOGRAM'">
                                            MAMMOGRAM
                                        </option>

                                        <option :value="'PAP SMEAR'">
                                            PAP SMEAR
                                        </option>

                                        <option :value="'BONE DENSITY'">
                                            BONE DENSITY
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date:</label><br>
                                    <input type="date" v-model="formMaintenance.date" placeholder="Date" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Facility/Provider:</label><br>
                                    <input type="text" v-model="formMaintenance.facility" placeholder="Facility/Provider" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('facility', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Abnormal Result:</label><br>
                                    <select class="--input" v-model="formMaintenance.abnormal_result">
                                        <option :value="'YES'">
                                            YES
                                        </option>

                                        <option :value="'NO'">
                                            NO
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('abnormal_result', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveMaintenance()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['TYPE', 'DATE', 'FACILITY/PROVIDER', 'ABNORMAL RESULT']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.maintenance" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}, {label: 'facility'}, {label: 'abnormal_result'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.maintenance[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10">
                            VACCINATION HISTORY
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <select class="--input" v-model="formVaccination.type">
                                        <option :value="'Last Tetanus Booster or TdaP'">
                                            Last Tetanus Booster or TdaP
                                        </option>

                                        <option :value="'Last Flu Vaccine:'">
                                            Last Flu Vaccine
                                        </option>
                                        
                                        <option :value="'Last Zoster Vaccine (Shingles)'">
                                            Last Zoster Vaccine (Shingles)
                                        </option>

                                        <option :value="'Last Pnuemovax (Pneumonia)'">
                                            Last Pnuemovax (Pneumonia)
                                        </option>

                                        <option :value="'Last Prevnar'">
                                            Last Prevnar
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date:</label><br>
                                    <input type="date" v-model="formVaccination.date" placeholder="Date" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveVaccination()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['TYPE', 'DATE']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.vaccinations" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.vaccinations[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10">
                            DISEASE HISTORY
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2" v-if="!formDisease.other">
                                    <label class="text-bold text-xl">Disease:</label><br>
                                    <select class="--input" v-model="formDisease.disease">
                                        <option :value="'Asthma'">
                                            Asthma
                                        </option>

                                        <option :value="'Cancer'">
                                            Cancer
                                        </option>
                                        
                                        <option :value="'Depression/Anxiety/Bipolar/Suicidal'">
                                            Depression/Anxiety/Bipolar/Suicidal
                                        </option>

                                        <option :value="'Diabetes'">
                                            Diabetes
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Emphysema (COPD)
                                        </option>

                                        <option :value="'Heart Disease'">
                                            Heart Disease
                                        </option>

                                        <option :value="'High Blood Pressure (hypertension)'">
                                            High Blood Pressure (hypertension)
                                        </option>

                                        <option :value="'High Cholesterol'">
                                            High Cholesterol
                                        </option>

                                        <option :value="'Hypothyroidism/Thyroid Disease'">
                                            Hypothyroidism/Thyroid Disease
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Renal (kidney) Disease
                                        </option>

                                        <option :value="'Stroke'">
                                           Stroke
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Renal (kidney) Disease
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('disease', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2" v-if="formDisease.other">
                                    <label class="text-bold text-xl">Disease:</label><br>
                                    <input type="text" v-model="formDisease.disease" placeholder="Disease" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('disease', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <input type="checkbox" v-model="formDisease.other">
                                    <label for="vehicle1"> Other</label><br>
                                </div>

                                <div class="w-full p-2" v-if="formDisease.disease == 'Cancer' || formDisease.type == 'Diabetes'">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <input type="text" v-model="formDisease.type" placeholder="Type" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Status:</label><br>
                                    <select class="--input" v-model="formDisease.status">
                                        <option :value="'Current'">
                                            Current
                                        </option>

                                        <option :value="'Past'">
                                            Past
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('status', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Comment:</label><br>
                                    <input type="text" v-model="formDisease.comment" placeholder="Comment" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('comment', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveDisease()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['DISEASE', 'TYPE', 'STATUS', 'COMMENT']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.diseases" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'disease'}, {label: 'type'}, {label: 'status'}, {label: 'comment'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.diseases[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10">
                            SURGERIES
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Type (specify left/right):</label><br>
                                    <input type="text" v-model="formSurgery.type" placeholder="Type" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date:</label><br>
                                    <input type="date" v-model="formSurgery.date" placeholder="Date" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">LOCATION/FACILITY:</label><br>
                                    <input type="text" v-model="formSurgery.facility" placeholder="LOCATION/FACILITY" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('facility', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveSurgery()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['TYPE', 'DATE', 'LOCATION/FACILITY']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.surgeries" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}, {label: 'facility'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.surgeries[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10">
                            WOMEN’S HEALTH HISTORY
                        </p>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date of Last Menstrual Cycle:</label><br>
                                    <input type="date" v-model="formWomen.dlmc" placeholder="Date of Last Menstrual Cycle:" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dlmc', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Total Number of Pregnancy:</label><br>
                                    <input type="text" v-model="formWomen.tnp" placeholder="Total Number of Pregnancy" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('tnp', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Pregnancy Complications:</label><br>
                                    <input type="text" v-model="formWomen.complications" placeholder="Pregnancy Complications" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('complications', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Age of First Menstruation:</label><br>
                                    <input type="text" v-model="formWomen.afm" placeholder="Age of First Menstruation" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('afm', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Age of Menopause:</label><br>
                                    <input type="text" v-model="formWomen.am" placeholder="Number of Live Births" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('am', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Number of Live Births:</label><br>
                                    <input type="text" v-model="formWomen.nlb" placeholder="Pregnancy Complications" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('nlb', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveWomen()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <div style="width: 70%" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['Date of Last Menstrual Cycle', 'Total Number of Pregnancies', 'Pregnancy Complications', 'Age of First Menstruation', 'Age of Menopause', 'Number of Live Births']" :key="column" style="text-transform: capitalize;">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.womens" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'dlmc'}, {label: 'tnp'}, {label: 'complications'}, {label: 'afm'}, {label: 'am'}, {label: 'nlb'}]" :key="i" class="cursor-pointer">
                                            <span>{{options.womens[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
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
import Toggle from '../Components/Toggle.vue';
import Table from "../Components/Table";
import axios from "axios";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle,
        Table
    },

    data(){
        return {
            activeTab: 'personal',
            formAllergy: {
                patient_id: this.options.patient,
                allergy: null,
                allergic_reaction: null
            },
            formMedication: {
                patient_id: this.options.patient,
                medications: null,
                dose: null,
                times_per_day: null
            },
            formMaintenance: {
                patient_id: this.options.patient,
                type: null,
                date: null,
                facility: null,
                abnormal_result: null
            },
            formVaccination: {
                patient_id: this.options.patient,
                type: null,
                date: null
            },
            formDisease: {
                patient_id: this.options.patient,
                disease: null,
                type: null,
                status: null,
                comment: null,
                other: null
            },
            formSurgery: {
                patient_id: this.options.patient,
                type: null,
                date: null,
                facility: null
            },
            formWomen: {
                patient_id: this.options.patient,
                dlmc: null,
                tnp: null,
                complications: null,
                afm: null,
                am: null,
                nlb: null
            },
            saveError: null
        }
    },

    watch: {
        activeTab(arg) {
            
        },

        'formDisease.other'(arg) {
            this.formDisease.disease = null
        }
    },

    mounted(){
        console.log(this.options)
    },

    methods: {
        saveAllergy() {
            axios.post(this.$root.route + '/history/save-allergy', this.formAllergy)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },

        saveMedication() {
            axios.post(this.$root.route + '/history/save-medication', this.formMedication)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },

        saveMaintenance() {
            axios.post(this.$root.route + '/history/save-maintenance', this.formMaintenance)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },

        saveVaccination(){
            axios.post(this.$root.route + '/history/save-vaccination', this.formVaccination)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },

        saveDisease(){
            axios.post(this.$root.route + '/history/save-disease', this.formDisease)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },
        saveSurgery(){
            axios.post(this.$root.route + '/history/save-surgery', this.formSurgery)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },
        saveWomen() {
                axios.post(this.$root.route + '/history/save-women', this.formWomen)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        }
    }
}

</script>

<style scoped>
.--bg_gray {
    background: #C0C0C0;
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
}

th {
    border: 1px solid black;
    background: #366422;
    color: white;
}

.--active__color {
    background: #B0BEC5;
}
</style>