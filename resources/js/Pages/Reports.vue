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

                <div class="w-full flex flex-col mt-4" v-if="activeTab == 'patient_report'">
                    <div class="w-full px-2">
                        <Table :columns="columns"  :rows="rows" :keys="keys" :selected.sync="selected"/>
                    </div>
                </div>

                <div class="w-full flex flex-col" v-if="activeTab == 'medicine_report'">
                    <div class="w-full mt-10" >

                        <span class="float-left cursor-pointer ml-6 text-xl mt-1"
                            @click="printMedicineReport()"
                        >
                            <i class="fa-solid fa-print"></i>
                        </span>

                        <select v-model="medicine_report_type" class="float-right mr-5" style="width: 200px; height: 40px; border: 1px solid black">
                            <option value="individual" v-if="auth.user_type == 'pharmacist'">Individual Report</option>
                            <option value="barangay">Barangay Report</option>
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
                        pdf-orientation="landscape"
                        pdf-content-width="100%"
                        ref="medicine_report"
                    >
                        <section slot="pdf-content">
                            <div class="w-full p-5">
                                <table class="w-full --table">
                                    <tr class="text-center">
                                        <th v-for="column in columns" :key="column" class="--th">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in rows" :key="index"
                                    >
                                        <td v-for="(k, i) in keys" :key="i" class="cursor-pointer --td">
                                            <span>{{rows[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
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
                        :enable-download="true"
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
                                                v-model="form.examined_dane"
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
                                                placeholder="Attending Physician" v-model="form.physician"
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
                                    v-model="form.examined_dane"
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
                                    placeholder="Attending Physician" v-model="form.physician"
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

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle,
        Table,
        VueHtml2pdf
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
                examined_dane: null,
                diagnosis: null,
                physician: null
            }
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
        activeTab(arg) {
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
                        'Medicine', 'Category', 'Dosage', 'Unit', 'Barangay', 'Quantity', 'Dispensed', 'Date'
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
                            label: 'dispensed',
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
                    'Medicine', 'Category', 'Dosage', 'Unit', 'Barangay', 'Quantity', 'Dispensed', 'Date'
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
                        label: 'dispensed',
                    },
                    {
                        label: 'date',
                    },
                ]
            }
        }
    },

    mounted(){
        if(this.auth.user_type == 'leader') {
            this.medicine_report_type = 'barangay'
        }

        if(this.auth.user_type == 'pharmacist') {
            this.activeTab = 'medicine_report';
        }

        if(this.activeTab == 'patient_report') {
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

    methods: {
        printReport(arg) {
            this.$refs[arg].generatePdf()
        },

        printMedicineReport() {
            this.$refs.medicine_report.generatePdf()
        }
    }
}

</script>

<style scoped>
.--bg_gray {
    background: #C0C0C0;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    display: none;
}

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
    padding-top: 20px;
    padding-bottom: 20px;
}

.--th {
    border: 1px solid black;
    background: #366422;
    color: #ffffff;
    padding-top: 20px;
    padding-bottom: 20px;
}
</style>