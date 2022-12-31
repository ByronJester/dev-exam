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

                <div class="w-full inline-flex mt-4" v-if="activeTab != 'medical_certificate'">
                    <!-- <select class="ml-3" style="width: 150px !important; height: 40px; border: 1px solid black; border-radius: 10px" v-model="barangay" v-if="auth.role != 3">
                        <option v-for="place in options.places" :key="place.name"
                            :value="place.name"
                        >
                            {{ place.name }}
                        </option>
                    </select> -->
                    <div style="width: 252px; margin-left: 10px;">
                        <Dropdown
                            :options="options.places"
                            v-on:selected="selectBarangay"
                            :disabled="false"
                            name="barangay"
                            :maxItem="5"
                            style="border: 1px solid black; border-radius: 3px"
                            placeholder="Please select barangay...">
                        </Dropdown>
                    </div>

                    <input type="text" style="width: 252px !important; height: 35px; border: 1px solid black; border-radius: 3px" class="ml-3 p-1" placeholder="Search...." v-model="search">

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
                days: null
            },
            barangay: null,
            search: null
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
                if(arg) {
                    if(this.search) {
                        this.rows = this.options.patients.filter( x => { return x.barangay == arg})
                            .filter(x => {
                                var name = x.name.toLowerCase();
                                var search = this.search.toLowerCase()
                                return name.includes(search)
                            });
                    } else {
                        this.rows = this.options.patients.filter( x => { return x.barangay == arg})
                    }
                    
                }
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
                                    var search = arg.toLowerCase()
                                    return name.includes(search)
                                });
                        } else {
                            this.rows = this.options.patientMedicines.filter(x => {
                                var name = x.patient_name.toLowerCase();
                                var search = arg.toLowerCase()
                                return name.includes(search)
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
                                    var search = arg.toLowerCase()
                                    return name.includes(search)
                                });
                        } else {
                            this.rows = this.options.barangayMedicines.filter(x => {
                                var name = x.name.toLowerCase();
                                var search = arg.toLowerCase()
                                return name.includes(search)
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
                    'Name', 'Barangay', 'Contact', 'Age', 'Gender', 'Consultation Forms'
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
                        label: 'consultation_form',
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
        }
    },

    mounted(){
        if(this.auth.user_type == 'leader' && this.activeTab == 'medicine_report') {
            this.medicine_report_type = 'barangay'
        }

        if(this.auth.user_type == 'pharmacist') {
            this.activeTab = 'medicine_report';
        }

        if(this.activeTab == 'patient_report') {
            this.columns = [
                'Name', 'Barangay', 'Contact', 'Age', 'Gender', 'Consultation Forms'
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
                    label: 'consultation_form',
                }
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
    },

    methods: {
        printReport(arg) {
            this.$refs[arg].generatePdf()
        },

        printMedicineReport() {
            this.$refs.medicine_report.generatePdf()
        },

        selectBarangay(arg){
            this.barangay = arg.name
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

input .dropdown-input {
    min-width: none;
    max-width: 500px;
}

</style>

