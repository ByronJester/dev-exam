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
                </div>

                <div class="w-full flex flex-col" v-if="activeTab == 'patient_report'">
    
                </div>

                <div class="w-full flex flex-col" v-else>
                    <div class="w-full mt-10" >
                        <select v-model="medicine_report_type" class="float-right mr-5" style="width: 200px; height: 40px; border: 1px solid black">
                            <option value="individual" v-if="auth.user_type == 'pharmacist'">Individual Report</option>
                            <option value="barangay">Barangay Report</option>
                        </select>
                    </div>


                    <div class="w-full mt-5 px-5">
                        <Table :columns="columns"  :rows="rows" :keys="keys"/>
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
            activeTab: 'patient_report',
            medicine_report_type: 'individual',
            columns: [
                
            ],
            keys : [
               
            ],
            rows: []
        }
    },

    watch: {
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
    },

    methods: {
        
    }
}

</script>

<style scoped>
.--bg_gray {
    background: #C0C0C0;
}
</style>