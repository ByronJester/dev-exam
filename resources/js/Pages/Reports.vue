<template>
    <div class="w-full h-screen">
        <Navigation :auth="auth">
            <div class="w-full flex flex-col">
                <div class="w-full flex flex-row mt-8" style="height: 5vh;">
                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer" v-if="auth.user_type != 'pharmacist'"
                        @click="activeTab = 'patient_report'"
                        :class="{'--bg_gray': activeTab == 'patient_report'}"
                    >
                        Patient Report
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer" v-if="auth.user_type == 'pharmacist' || auth.user_type == 'leader'"
                        :class="{'--bg_gray': activeTab == 'medicine_report'}"
                        @click="activeTab = 'medicine_report'"
                    >
                        Medicine Report
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer" v-if="auth.user_type == 'doctor'"
                        :class="{'--bg_gray': activeTab == 'medical_certificate'}"
                        @click="activeTab = 'medical_certificate'"
                    >
                        Medical Certificate
                    </div>
                </div>

                <div class="flex flex-row mt-20" v-if="activeTab != 'medical_certificate'" :style="{'width': activeTab == 'patient_report' ? '70%' : '50%'}">
                    <div class="w-3/4 flex flex-col">
                        <div class="ml-3 font-bold">
                            Select Barangay:
                        </div>

                        <div style="width: 252px;" class="ml-3">
                            <Dropdown
                                :options="options.places"
                                v-on:selected="selectBarangay"
                                :disabled="auth.role == 3"
                                name="barangay"
                                :maxItem="options.places.length"
                                style="border: 1px solid black; border-radius: 3px"
                                :placeholder="barangay"> 
                            </Dropdown>
                        </div>
                    </div>

                    <div class="w-1/4 flex flex-col ml-3" v-if="activeTab == 'patient_report'">
                        <div class="font-bold">
                            Age From:
                        </div>

                        <div style="width: 100%;">
                            <input type="text" style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="p-1 text-center" v-model="age.start" @keypress="validateNumber($event)">
                        </div>
                    </div>

                    <div class="w-1/5 flex flex-col ml-3" v-if="activeTab == 'patient_report'">
                        <div class="font-bold">
                            Age To:
                        </div>

                        <div style="width: 100%">
                            <input type="text" style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="p-1 text-center" v-model="age.end" @keypress="validateNumber($event)">
                        </div>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3" v-if="activeTab == 'medicine_report'">
                        <div class="font-bold">
                            Search:
                        </div>

                        <div style="width: 100%;">
                            <input type="text" style="width: 252px !important; height: 35px; border: 1px solid black; border-radius: 3px" class="ml-3 p-1" placeholder="Search...." v-model="search">
                        </div>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3">
                        <div class="font-bold">
                            Date From:
                        </div>

                        <div style="width: 100%;">
                            <input type="date" style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="p-1 text-center" v-model="date.start">
                        </div>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3">
                        <div class="font-bold">
                            Date To:
                        </div>

                        <div style="width: 100%;">
                            <input type="date" style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="p-1 text-center" v-model="date.end">
                        </div>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3" v-if="activeTab == 'patient_report'">
                        <div class="font-bold">
                            Gender:
                        </div>

                        <div style="width: 100%;">
                            <!-- <input type="text" style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="p-1 text-center" v-model="date.end"> -->
                            <select style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="text-center" v-model="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3" v-if="activeTab == 'patient_report'">
                        <div class="font-bold">
                            Form:
                        </div>

                        <div style="width: 100%;">
                            <!-- <input type="text" style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="p-1 text-center" v-model="date.end"> -->
                            <select style="width: 100% !important; height: 35px; border: 1px solid black; border-radius: 3px" class="text-center" v-model="formType">
                                <option value="TB-Dots Form">TB-Dots Form</option>
                                <option value="Pregnancy Form">Pregnancy Form</option>
                                <option value="Prenatal Form">Prenatal Form</option>
                                <option value="Postnatal Form">Postnatal Form</option>
                                <option value="Nutrition Form">Nutrition Form</option>
                                <option value="Vaccination Form">Vaccination Form</option>
                                <option value="Deworming Form">Deworming Form</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3">

                        <button style="background: #BB5A5A; width: 100%; height: 35px; border-radius: 3px" class="text-center text-xs py-2 text-white mt-6 "
                            @click="clearFilter()"
                        >
                            Clear
                        </button>
                    </div>

                    <div class="w-2/5 flex flex-col ml-3">

                        <button style="background: #366422; width: 100%; height: 35px; border-radius: 3px" class="text-center text-xs py-2 text-white mt-6 "
                            @click="filterRows()"
                        >
                            Filter
                        </button>
                    </div>
                </div>

                

                <div class="w-full flex flex-col mt-4" v-if="activeTab == 'patient_report'">
                    <div class="w-full mt-2" >
                        <span class="float-right cursor-pointer mr-3 text-xl mt-1"
                            @click="printPatientReport()"
                        >
                            <i class="fa-solid fa-print"></i>
                        </span>
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
                        ref="patient_report"
                    >
                        <section slot="pdf-content">
                            <div class="w-full flex flex-col p-5">
                                <div class="w-full">
                                    <!-- <span class="text-sm font-bold float-left">
                                       Total Reports: {{ options.reportCount }}
                                    </span> -->

                                    <span class="text-sm font-bold float-right">
                                       {{ options.now }}
                                    </span>
                                </div>

                                <div class="w-full h-full flex justify-center items-center">
                                    <img src="/images/logo1.png" class="py-1" style="height: 80px; width: 80px"/>
                                </div>

                                <div class="w-full text-center">
                                    <p style="font-weight: 500; letter-spacing: 1.5px" class="text-sm">
                                        Republic of the Philippines<br>
                                        Province of Batangas <br>
                                        Municipal Health Unit of Balayan Batangas
                                    </p>
                                </div>

                                <div class="w-full text-center mt-5">
                                    <p style="font-weight: 700; letter-spacing: 2px" class="text-md">
                                        <!-- PATIENT REPORTS --> {{ formType ? formType.replace('Form', ''): 'Patients' }} Report
                                    </p>
                                </div>

                                <div class="w-full mt-5">
                                    <span class="text-sm font-bold float-left">
                                        {{ auth.first_name + ' ' + auth.last_name }} ({{ getUserType(auth) }})
                                    </span>
                                </div>

                                <div class="w-full mt-5 inline-flex">
                                    <p class="text-xs float-left" v-if="barangay">
                                        <span class="font-bold">Barangay:</span> {{ barangay }}
                                    </p>

                                    <p class="text-xs float-left ml-3" v-if="date.start && date.end">
                                        <span class="font-bold">Date:</span> {{ new Date(date.start).toDateString().substring(3) }} - {{ new Date(date.end).toDateString().substring(3) }}
                                    </p>

                                    <p class="text-xs float-left ml-3" v-if="age.start && age.end">
                                       <span class="font-bold">Age:</span> {{age.start}} - {{age.end}}
                                    </p>

                                    <p class="text-xs float-left ml-3" v-if="gender">
                                        <span class="font-bold">Gender:</span> {{gender}}
                                    </p>
                                </div>

                                <div class="w-full mt-5">
                                    <table class="w-full --table">
                                        <tr class="text-center text-sm">
                                            <th v-for="column in columns" :key="column" class="--th">
                                                {{ column }}
                                            </th>
                                        </tr>

                                        <tr class="text-center text-xs"
                                            v-for="(l, index) in rows" :key="index"
                                        >
                                            <td v-for="(k, i) in keys" :key="i" class="cursor-pointer --td">
                                                <span>{{rows[index][k.label] }}</span> 
                                            </td>
                                        </tr>

                                    </table>
                                </div>

                                <div class="w-full">
                                    <p class="float-right">
                                       <span class="font-bold">Total:</span> {{ options.reportCount }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 
                    
                    <div class="w-full px-2">
                        <Table :columns="columns"  :rows="rows" :keys="keys" class="w-full"/>

                        <div class="w-full flex justify-center items-center mt-2">
                            <button style="width: 50px; height: 50px" @click="prev()">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>

                            <button style="width: 50px; height: 50px "  class="cursor-help">
                                {{ options.page }}
                            </button>

                            <button style="width: 50px; height: 50px" @click="next()">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col" v-if="activeTab == 'medicine_report'">
                    <div class="w-full mt-5" >
                        <span class="float-left cursor-pointer ml-6 text-xl mt-1"
                            @click="printMedicineReport()"
                        >
                            <i class="fa-solid fa-print"></i>
                        </span>

                        <select v-model="medicine_report_type" class="float-right mr-5" style="width: 200px; height: 40px; border: 1px solid black" >
                            <option value="individual">Individual Report</option>
                            <option value="barangay" v-if="auth.role != 3">Barangay Report</option>
                        </select>
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
                        ref="medicine_report"
                    >
                        <section slot="pdf-content">
                            <div class="w-full flex flex-col p-5">
                                <div class="w-full">
                                    <!-- <span class="text-sm font-bold float-left">
                                       Total Reports: {{ rows.length }}
                                    </span> -->

                                    <span class="text-sm font-bold float-right">
                                       {{ options.now }}
                                    </span>
                                </div>

                                <div class="w-full h-full flex justify-center items-center">
                                    <img src="/images/logo1.png" class="py-1" style="height: 80px; width: 80px"/>
                                </div>

                                <div class="w-full text-center">
                                    <p style="font-weight: 500; letter-spacing: 1.5px" class="text-sm">
                                        Republic of the Philippines<br>
                                        Province of Batangas <br>
                                        Municipal Health Unit of Balayan Batangas
                                    </p>
                                </div>

                                <div class="w-full text-center mt-5">
                                    <p style="font-weight: 700; letter-spacing: 2px" class="text-md">
                                        Medicines Report
                                    </p>
                                </div>

                                <div class="w-full mt-5">
                                    <span class="text-sm font-bold float-left">
                                        {{ auth.first_name + ' ' + auth.last_name }} ({{ getUserType(auth) }})
                                    </span>
                                </div>

                                <div class="w-full mt-5 inline-flex">
                                    <p class="text-xs float-left" v-if="barangay">
                                        <span class="font-bold">Barangay:</span> {{ barangay }}
                                    </p>

                                    <p class="text-xs float-left ml-3" v-if="date.start && date.end">
                                        <span class="font-bold">Date:</span> {{ new Date(date.start).toDateString().substring(3) }} - {{ new Date(date.end).toDateString().substring(3) }}
                                    </p>
                                </div>

                                <div class="w-full mt-2">
                                    <table class="w-full --table">
                                        <tr class="text-center text-sm">
                                            <th v-for="column in columns" :key="column" class="--th">
                                                {{ column }}
                                            </th>
                                        </tr>

                                        <tr class="text-center text-xs"
                                            v-for="(l, index) in rows" :key="index"
                                        >
                                            <td v-for="(k, i) in keys" :key="i" class="cursor-pointer --td">
                                                <span>{{rows[index][k.label] }}</span> 
                                            </td>
                                        </tr>

                                    </table>
                                </div>

                                <div class="w-full">
                                    <span class="float-right">
                                       <span class="font-bold">Total:</span> {{ rows.length }}
                                    </span>
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf> 


                    <div class="w-full mt-5 px-5">
                        <Table :columns="columns"  :rows="rows" :keys="keys"/>
                    </div>
                </div>

                <div class="w-full flex justify-center items-center flex-col mt-20 mb-60" v-if="activeTab == 'medical_certificate'">
                    <div class="w-full mb-5">
                        <span class="float-right text-xl mr-20 cursor-pointer" style="border: 1px solid black;" @click="printReport('med_cert')">
                            <i class="fa-solid fa-print px-2"></i>
                        </span>
                    </div>

                    <VueHtml2pdf
                        :show-layout="false"
                        :float-layout="true"
                        :enable-download="false"
                        :preview-modal="true"
                        :paginate-elements-by-height="1000"
                        :filename="Math.random().toString(36).slice(2)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="med_cert"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <div style="width: 100%;" class="flex flex-col">
                                    <div class="w-full h-full flex justify-center items-center">
                                        <img src="/images/logo1.png" class="py-1" style="height: 130px; width: 130px"/>
                                    </div>

                                    <div class="w-full text-center">
                                        <p style="font-weight: 500" class="text-lg">
                                            Republic of the Philippines<br>
                                            Province of Batangas <br>
                                            Municipal Health Unit of Balayan Batangas
                                        </p>
                                    </div>

                                    <div class="w-full text-center mt-12">
                                        <p style="font-weight: 700" class="text-2xl">
                                            MEDICAL CERTIFICATE
                                        </p>
                                    </div>

                                    <div class="w-full mt-10">
                                        <input type="date" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mr-2"
                                            v-model="form.date_now"
                                        >
                                    </div>

                                    <div class="w-full font-bold mt-5">
                                        To Whom it May Concern:
                                    </div>

                                    <div class="w-full flex flex-col font-bold mt-10">
                                        <div class="w-full inline-flex">
                                            <span class="ml-5 text-sm">
                                                This is to certify that
                                            </span>

                                            <input type="text" style="width: 300px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                                placeholder="Name of Patient" v-model="form.name"
                                            >

                                            <span class="mx-2 text-sm">
                                                of
                                            </span>

                                            <input type="text" style="width: 560px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                                placeholder="Address ( Street, Barangay, Town, Province )" v-model="form.address"
                                            >
                                        </div>

                                        <div class="w-full inline-flex mt-4">
                                            <span class="text-sm">
                                                Was examined and treated at the Municipal Health Office on
                                            </span>

                                            <input type="date" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                                v-model="form.examined_date"
                                            >

                                            <span class="mx-2 text-sm">
                                                with the following diagnosis:
                                            </span>
                                        </div>

                                        <div class="w-full inline-flex mt-4">
                                            <input type="text" style="width: 100%; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-left float-right mx-2"
                                                v-model="form.diagnosis"
                                            >
                                        </div>

                                        <div class="w-full inline-flex mt-4">
                                            <span class="text-sm">
                                                And would need medical attention for 
                                            </span>

                                            <input type="text" style="width: 300px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                                placeholder="Days" v-model="form.days"
                                            >

                                            <span class="mx-2 text-sm">
                                                days barring complication.
                                            </span>
                                        </div>
                                    </div>

                                    <div class="w-full mt-10">
                                        <input type="text" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mr-2"
                                            placeholder="Attending Physician" v-model="form.physician"
                                        >
                                    </div>

                                    <div class="w-full mt-10">
                                        <input type="text" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mr-2"
                                            placeholder="License No." v-model="form.license"
                                        >
                                    </div>
                                    
                                </div>
                            </div>
                        </section>
                    </VueHtml2pdf>

                    <div style="width: 60%;" class="flex flex-col">
                        <div class="w-full text-center">
                            <div class="w-full h-full flex justify-center items-center">
                                <img src="/images/logo1.png" class="py-1" style="height: 130px; width: 130px"/>
                            </div>
                            
                            <p style="font-weight: 500" class="text-lg">
                                Republic of the Philippines<br>
                                Province of Batangas <br>
                                Municipal Health Unit <br>
                                Balayan Batangas
                            </p>
                        </div>

                        <div class="w-full text-center mt-12">
                            <p style="font-weight: 700" class="text-2xl">
                                MEDICAL CERTIFICATE
                            </p>
                        </div>

                        <div class="w-full mt-10">
                            <input type="date" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mr-2"
                                v-model="form.date_now"
                            >
                        </div>

                        <div class="w-full font-bold mt-5">
                            To Whom it May Concern:
                        </div>

                        <div class="w-full flex flex-col font-bold mt-10">
                            <div class="w-full inline-flex">
                                <span class="ml-20">
                                    This is to certify that
                                </span>

                                <input type="text" style="width: 300px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                    placeholder="Name of Patient" v-model="form.name"
                                >

                                <span class="mx-2">
                                    of
                                </span>

                                <input type="text" style="width: 560px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                    placeholder="Address ( Street, Barangay, Town, Province )" v-model="form.address"
                                >
                            </div>

                            <div class="w-full inline-flex mt-4">
                                <span>
                                    Was examined and treated at the Municipal Health Office on
                                </span>

                                <input type="date" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                    v-model="form.examined_date"
                                >

                                <span class="mx-2">
                                    with the following diagnosis:
                                </span>
                            </div>

                            <div class="w-full inline-flex mt-4">
                                <input type="text" style="width: 100%; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-left float-right mx-2"
                                    v-model="form.diagnosis"
                                >
                            </div>

                            <div class="w-full inline-flex mt-4">
                                <span>
                                    And would need medical attention for 
                                </span>

                                <input type="text" style="width: 300px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mx-2"
                                    v-model="form.days"
                                >

                                <span class="mx-2">
                                    days barring complication.
                                </span>
                            </div>
                        </div>

                        <div class="w-full mt-10">
                            <input type="text" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mr-2"
                                placeholder="Attending Physician" v-model="form.physician"
                            >
                        </div>

                        <div class="w-full mt-10">
                            <input type="text" style="width: 200px; border-bottom: 1px solid black" class="focus:--borderless hover:--borderless text-center float-right mr-2"
                                placeholder="License No." v-model="form.license"
                            >
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
import VueHtml2pdf from 'vue-html2pdf'
import Dropdown from 'vue-simple-search-dropdown';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle,
        Table,
        VueHtml2pdf,
        Dropdown
    },

    data(){
        return {
            activeTab: 'patient_report',
            medicine_report_type: 'individual',
            columns: [
                
            ],
            keys : [
               
            ],
            rows: [],
            selected: null,
            form: {
                name: null,
                address : null,
                date_now: null,
                examined_date: null,
                diagnosis: null,
                physician: null,
                days: null,
                license: null
            },
            barangay: null,
            search: null,
            startYear: null,
            endYear: null,
            years: [],
            selectedYear: null,
            date: {
                start: null,
                end: null
            },
            age: {
                start: null,
                end: null
            },
            gender: null,
            formType: null,
            hasSelectedBarangay: false,
            page: 1
        }
    },

    watch: {
        selected(arg){
            Inertia.get(
                this.$root.route + '/patients/' + arg.id + '/' + true,
                {
                    onSuccess: () => { },
                },
            );
        },
        barangay(arg){
            if(this.activeTab == 'patient_report') {
                // if(arg) {
                //     if(this.search) {
                //         this.rows = this.options.patients.filter( x => { return x.barangay == arg})
                //             .filter(x => {
                //                 var name = x.name.toLowerCase();
                //                 var search = this.search.toLowerCase()
                //                 return name.includes(search)
                //             });
                //     } else {
                //         this.rows = this.options.patients.filter( x => { return x.barangay == arg})
                //     }
                    
                // }
            } else {
                if(this.medicine_report_type == 'individual') {
                    if(arg) {
                        if(this.search) {
                            this.rows = this.options.patientMedicines.filter( x => { return x.place_name == arg})
                                .filter(x => {
                                    var name = x.patient_name.toLowerCase();
                                    var search = this.search.toLowerCase()
                                    return name.includes(search)
                                });
                        } else {
                            this.rows = this.options.patientMedicines.filter( x => { return x.place_name == arg})
                        }
                    } 
                } else {
                    if(arg) {
                        if(this.search) {
                            this.rows = this.options.barangayMedicines.filter( x => { return x.place_name == arg})
                                .filter(x => {
                                    var name = x.name.toLowerCase();
                                    var search = this.search.toLowerCase()
                                    return name.includes(search)
                                });
                        } else {
                            this.rows = this.options.barangayMedicines.filter( x => { return x.place_name == arg})
                        }
                    } 
                    
                }
            }
        },
        search(arg) {
            // this.barangay = null

            if(this.activeTab == 'patient_report') {
                if(arg) {
                    if(this.barangay) {
                        this.rows = this.options.patients
                            .filter( y => { return y.barangay == this.barangay})
                            .filter(x => {
                                var name = x.name.toLowerCase();
                                var search = arg.toLowerCase()
                                var form = x.consultation_form.toLowerCase()
                                return name.includes(search) || form.includes(search)
                            });
                    } else {
                        this.rows = this.options.patients.filter(x => {
                            var name = x.name.toLowerCase();
                            var search = arg.toLowerCase()
                            var form = x.consultation_form.toLowerCase()
                            return name.includes(search) || form.includes(search)
                        });
                    }
                    
                } else {
                    this.rows = this.options.patients
                }
                
            } else {
                if(this.medicine_report_type == 'individual') {
                    if(arg) {

                        if(this.barangay) {
                            this.rows = this.options.patientMedicines
                                .filter( y => { return y.place_name == this.barangay})
                                .filter(x => {
                                    var name = x.patient_name.toLowerCase();
                                    var category = x.category.toLowerCase();
                                    var unit = x.unit.toLowerCase();
                                    var search = arg.toLowerCase()
                                    return name.includes(search) || category.includes(search) || unit.includes(search);
                                });
                        } else {
                            this.rows = this.options.patientMedicines.filter(x => {
                                var name = x.patient_name.toLowerCase();
                                var category = x.category.toLowerCase();
                                var unit = x.unit.toLowerCase();
                                var search = arg.toLowerCase()
                                return name.includes(search) || category.includes(search) || unit.includes(search);
                            });
                        }
                    } else {
                        if(this.barangay) {
                            this.rows = this.options.patientMedicines
                                .filter( y => { return y.place_name == this.barangay})
                        } else {
                            this.rows = this.options.patientMedicines
                        }
                    }
                    
                } else {
                    if(arg) {

                        if(this.barangay) {
                            this.rows = this.options.barangayMedicines
                                .filter( y => { return y.place_name == this.barangay})
                                .filter(x => {
                                    var name = x.name.toLowerCase();
                                    var category = x.category.toLowerCase();
                                    var unit = x.unit.toLowerCase();
                                    var search = arg.toLowerCase()
                                    return name.includes(search) || category.includes(search) || unit.includes(search);
                                });
                        } else {
                            this.rows = this.options.barangayMedicines.filter(x => {
                                var name = x.name.toLowerCase();
                                var category = x.category.toLowerCase();
                                var unit = x.unit.toLowerCase();
                                var search = arg.toLowerCase()
                                return name.includes(search) || category.includes(search) || unit.includes(search);
                            });
                        }
                    } else {
                        if(this.barangay) {
                            this.rows = this.options.barangayMedicines
                                .filter( y => { return y.place_name == this.barangay})
                        } else {
                            this.rows = this.options.barangayMedicines
                        }
                    }
                    
                }
            }
        },
        activeTab(arg) {
            this.startYear = 1800;
            this.endYear = new Date().getFullYear();

            for (var i = this.endYear; i > this.startYear; i--){
                $('#yearpicker').append($('<option />').val(i).html(i));
            }

            if(arg == 'medicine_report') {
                if(this.medicine_report_type == 'individual') {
                    this.rows = this.options.patientMedicines 

                    this.columns = [
                        'Medicine', 'Category', 'Dosage', 'Unit', 'Patient', 'Barangay', 'Quantity', 'Date'
                    ]

                    this.keys = [
                        {
                            label: 'name',
                        },
                        {
                            label: 'category',
                        },
                        {
                            label: 'dosage',
                        },
                        {
                            label: 'unit',
                        },
                        {
                            label: 'patient_name',
                        },
                        {
                            label: 'barangay',
                        },
                        {
                            label: 'quantity',
                        },
                        {
                            label: 'date',
                        },
                    ]
                    
                } else {
                    this.rows = this.options.barangayMedicines
                    
                    this.columns = [
                        'Medicine', 'Category', 'Dosage', 'Unit', 'Barangay', 'Quantity', 'Date'
                    ]

                    this.keys = [
                        {
                            label: 'name',
                        },
                        {
                            label: 'category',
                        },
                        {
                            label: 'dosage',
                        },
                        {
                            label: 'unit',
                        },
                        {
                            label: 'place_name',
                        },
                        {
                            label: 'quantity',
                        },
                        {
                            label: 'date',
                        },
                    ]
                }
            } else {
                this.columns = [
                    'Name', 'Barangay', 'Contact', 'Age', 'Gender'
                ];

                this.keys = [
                    {
                        label: 'name',
                    },
                    {
                        label: 'barangay',
                    },
                    {
                        label: 'phone',
                    },
                    {
                        label: 'age',
                    },
                    {
                        label: 'gender',
                    }
                ]

                this.rows = this.options.patients
            }
        },

        medicine_report_type(arg) {
            this.search = null
            this.barangay = null

            if(arg == 'individual') {
                this.rows = this.options.patientMedicines 

                this.columns = [
                    'Medicine', 'Category', 'Dosage', 'Unit', 'Patient', 'Barangay', 'Quantity', 'Date'
                ]

                this.keys = [
                    {
                        label: 'name',
                    },
                    {
                        label: 'category',
                    },
                    {
                        label: 'dosage',
                    },
                    {
                        label: 'unit',
                    },
                    {
                        label: 'patient_name',
                    },
                    {
                        label: 'barangay',
                    },
                    {
                        label: 'quantity',
                    },
                    {
                        label: 'date',
                    },
                ] 
                
            } else {
                this.rows = this.options.barangayMedicines
                
                this.columns = [
                    'Medicine', 'Category', 'Dosage', 'Unit', 'Barangay', 'Quantity', 'Date'
                ]

                this.keys = [
                    {
                        label: 'name',
                    },
                    {
                        label: 'category',
                    },
                    {
                        label: 'dosage',
                    },
                    {
                        label: 'unit',
                    },
                    {
                        label: 'place_name',
                    },
                    {
                        label: 'quantity',
                    },
                    {
                        label: 'date',
                    },
                ]
            }
        },
        selectedYear(arg) {
            if(this.activeTab == 'patient_report') {
                this.columns = [
                    'Name', 'Barangay', 'Contact', 'Age', 'Gender', 'Date'
                ];

                this.keys = [
                    {
                        label: 'name',
                    },
                    {
                        label: 'barangay',
                    },
                    {
                        label: 'phone',
                    },
                    {
                        label: 'age',
                    },
                    {
                        label: 'gender',
                    },
                    {
                        label: 'display_date',
                    }, 
                ]

                this.rows = this.options.patients.filter(x => {
                    return x.display_year == arg
                })
            }
        }
    },

    mounted(){
        this.startYear = 1800;
        this.endYear = new Date().getFullYear();

        for (var i = this.endYear; i > this.startYear; i--){
            // $('#yearpicker').append($('<option />').val(i).html(i));
            this.years.push(i)
        }

        // this.selectedYear = this.years[0]

        if(this.auth.user_type == 'leader' && this.activeTab == 'medicine_report') {
            this.medicine_report_type = 'barangay'
        }

        if(this.auth.user_type == 'pharmacist') {
            this.activeTab = 'medicine_report';
        }

        if(this.activeTab == 'patient_report') {
            this.columns = [
                'Name', 'Barangay', 'Contact', 'Age', 'Gender', 'Date'
            ];

            this.keys = [
                {
                    label: 'name',
                },
                {
                    label: 'barangay',
                },
                {
                    label: 'phone',
                },
                {
                    label: 'age',
                },
                {
                    label: 'gender',
                },
                {
                    label: 'display_date',
                }, 
            ]

            this.rows = this.options.patients
        }

        if(this.auth.user_type == 'pharmacist') {
            this.activeTab = 'medicine_report'
        } else {
            this.activeTab = 'patient_report'
        }

        var date = new Date();

        var currentDate = date.toISOString().slice(0,10);

        this.form.date_now = currentDate
        this.form.examined_date = currentDate

        this.form.physician = this.auth.first_name + ' ' + this.auth.last_name

        this.date.start = this.options.date_start
        this.date.end = this.options.date_end
        this.age.start = this.options.age_start
        this.age.end = this.options.age_end
        this.gender = this.options.gender
        this.formType = this.options.formType
        this.barangay = this.options.barangay
        this.page = this.options.page

        var today = new Date();

        var start = new Date(today.getFullYear(), today.getMonth(), 2).toISOString().slice(0,10);
        var end = new Date(today.getFullYear(), today.getMonth() + 1, 1).toISOString().slice(0,10);

        this.date.start = start
        this.date.end = end
    },

    methods: {
        printReport(arg) {
            this.$refs[arg].generatePdf()
        },

        printMedicineReport() {
            this.$refs.medicine_report.generatePdf()
        },

        printPatientReport() {
            this.$refs.patient_report.generatePdf()
        },

        selectBarangay(arg){
            if(arg) {
                this.barangay = arg.name

                this.hasSelectedBarangay = true
            }
        },

        validateNumber(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
            // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\./;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        },

        filterRows() {
            if(this.activeTab == 'medicine_report') {
                this.rows = this.rows.filter(x => {
                    var createdAt = new Date(x.created_at);
                    return createdAt >= new Date(this.date.start) && createdAt <= new Date(this.date.end);
                })
            }

            if(this.activeTab == 'patient_report') {
                var res = {
                    date_start: this.date.start,
                    date_end: this.date.end,
                    age_start: this.age.start,
                    age_end: this.age.end,
                    gender: this.gender,
                    formType: this.formType,
                    barangay: this.barangay
                }

                Inertia.get(
                    this.$root.route + '/reports', res, 
                    {
                        onSuccess: (res) => { 

                        },
                    },
                );
            }
        },

        prev(){
            if(this.page > 1) {
                var res = {
                    date_start: this.date.start,
                    date_end: this.date.end,
                    age_start: this.age.start,
                    age_end: this.age.end,
                    gender: this.gender,
                    formType: this.formType,
                    barangay: this.barangay,
                    page: parseInt(this.page) - 1
                }

                Inertia.get(
                    this.$root.route + '/reports', res, 
                    {
                        onSuccess: (res) => { 

                        },
                    },
                );
            }

            
        },

        next() {
            if(this.page < this.options.pageCount) {
                var res = {
                    date_start: this.date.start,
                    date_end: this.date.end,
                    age_start: this.age.start,
                    age_end: this.age.end,
                    gender: this.gender,
                    formType: this.formType,
                    barangay: this.barangay,
                    page: parseInt(this.page) + 1
                }

                Inertia.get(
                    this.$root.route + '/reports', res, 
                    {
                        onSuccess: (res) => { 
                            console.log(res)
                        },
                    },
                );
            }
        },

        clearFilter(){
            Inertia.get(
                this.$root.route + '/reports',
                {
                    onSuccess: (res) => { 

                    },
                },
            );
        },

        getUserType(user) {
            var user_type = null;

            if(user.role == 1) {
                user_type = 'Admin';
            }

            if(user.role == 2) {
                switch(user.user_type) {
                    case 'doctor':
                        user_type = 'RHU - Doctor';
                        break;

                    case 'pharmacist':
                        user_type = 'Pharmacist';
                        break;

                    case 'midwife':
                        user_type = 'Chief Midwife';
                        break;

                    case 'nurse':
                        user_type = 'Nurse';
                        break;
                }
            }

            if(user.role == 3) {
                switch(user.user_type) {
                    case 'leader':
                        user_type = 'BHW - Leader';
                        break;
                    case 'midwife':
                        user_type = 'Barangay Midwife';
                        break;

                    case 'member':
                        user_type = 'BHW - Member';
                        break;
                }
            }

            return user_type;
        },
    }
}

</script>

<style scoped>
.--bg_gray {
    background: #C0C0C0;
}

/* input[type="date"]::-webkit-calendar-picker-indicator {
    display: none;
} */

input {
    outline: none;
}

.--borderless {
    border: 1px solid transparent;
}

.--table {
    border-collapse: collapse;
    border-radius: 5px;
    border-style: hidden;
    box-shadow: 0 0 0 1px black;
}

.--td {
    border: 1px solid black;
    padding-top: 10px;
    padding-bottom: 10px;
}

.--th {
    border: 1px solid black;
    background: #366422;
    color: #ffffff;
    padding-top: 10px;
    padding-bottom: 10px;
}

input .dropdown-input {
    min-width: none;
    max-width: 500px;
}

</style>

