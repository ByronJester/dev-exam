<template>
    <div class="w-full h-screen">
        <Navigation :auth="auth">
            <div class="px-4 pt-12">
                <div v-if="!newUser" class="w-full px-12" >
                    <div class="text-2xl font-bold w-full inline-flex"
                        style="border-bottom: 1px solid black"
                    >
                        <span class="cursor-pointer" :class="{'text-blue-500': activeTab == 'medicines'}" @click="activeTab = 'medicines'">
                            Medicine Dispensed
                        </span>

                        <span class="ml-4 cursor-pointer" :class="{'text-blue-500': activeTab == 'stock'}" @click="activeTab = 'stock'">
                            Medicine Stock
                        </span>
                    </div>

                    <div class="w-full" v-if="activeTab == 'medicines'">
                        <div class="flex flex-row mb-5 mt-10">
                            <div class="w-10/12 inline-flex">
                                <input type="text" class="--search pl-5 mr-2"
                                    v-model="form.search" @input="initiateSearch()"
                                    placeholder="Search...."
                                >

                                <select class="mr-2" style="width: 150px !important; height: 35px; border: 1px solid black; border-radius: 3px" v-model="dispensed_type" v-if="auth.role != 3">
                                    <option :value="'barangay'">
                                        Barangay
                                    </option>

                                    <option :value="'individual'">
                                        Individual
                                    </option>
                                </select>


                                <!-- <select class="--input" style="width: 150px !important" v-model="barangay" v-if="dispensed_type == 'barangay' && auth.role != 3">
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
                            </div>

                            <div class="w-2/12">
                                <button class="text-black float-right p-2"
                                    style="border: 1px solid black; border-radius: 5px"
                                    @click="openModal()"
                                >
                                    <i class="fa-solid fa-house-chimney-medical fa-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div class="w-full h-full mr-2">
                                <Table :columns="columns"  :rows="medecineList" :keys="keys"/>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex flex-col" v-else>
                        <div class="w-full inline-flex mt-4">
                            <input type="text" class="--search pl-5 mr-2"
                                v-model="search"
                                placeholder="Search...."
                            >
                        </div>

                        <div class="w-full mt-4">
                            <button class="text-black float-right p-2 mt-2"
                                style="border: 1px solid black; border-radius: 5px; width: 50px"
                                @click="openStockModal()"
                                v-if="auth.user_type == 'pharmacist'"
                            >
                                <i class="fa-solid fa-house-chimney-medical fa-2xl"></i>
                            </button>
                        </div>

                        <div class="flex flex-col">
                            <div class="w-full h-full mr-2">
                                <Table :columns="columns"  :rows="medicineStocts" :keys="keys"/>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="medicineModal" class="medicineModal">
                    <div class="medicine-modal-content flex flex-col" style="width: 30%">
                        <div class="w-full">
                            <span class="text-lg font-bold">
                                New Medicine
                            </span>
                            <span class="float-right cursor-pointer"
                                @click="closeModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="my-1 flex flex-row">

                                <div class="w-full mr-2" v-if="auth.role != '3'">
                                    <label class="text-bold">Medicine:</label><br>
                                    <select class="--input" v-model="formData.medicine_id">
                                        <option v-for="medicine in options.medicines.filter(x => {return options.stocks.includes(x.id)})" :key="medicine.id" :value="medicine.id">
                                            {{ medicine.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_id', saveError)}} </span>
                                </div>

                                <div class="w-full mr-2" v-else>
                                    <label class="text-bold">Medicine:</label><br>
                                    <select class="--input" v-model="formData.medicine_id">
                                        <option v-for="medicine in options.medicines" :key="medicine.id" :value="medicine.id">
                                            {{ medicine.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_id', saveError)}} </span>
                                </div>

                                <div class="w-full mr-2">
                                    <label class="text-bold">Category:</label><br>
                                    <select class="--input" v-model="formData.medicine_category_id">
                                        <option v-for="category in options.categories.filter(x => {return categories.includes(x.id)})" :key="category.id" :value="category.id">
                                            {{ category.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_category_id', saveError)}} </span>
                                </div>

                                <div class="mr-2">
                                    <label class="text-bold">Dosage:</label><br>
                                    <input type="number" class="--input" v-model="formData.dosage">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dosage', saveError)}} </span>
                                </div>

                                <div class="w-full mr-2">
                                    <label class="text-bold">Unit:</label><br>
                                    <select class="--input" v-model="formData.medicine_unit_id">
                                        <option v-for="unit in options.units.filter(x => {return units.includes(x.id)})" :key="unit.id" :value="unit.id">
                                            {{ unit.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_unit_id', saveError)}} </span>
                                </div>

                            </div>

                            <div class="my-1">
                                <label class="text-bold">Quantity:</label><br>
                                <input type="number" class="--input" v-model="formData.quantity">
                                <span class="text-xs text-red-500 ml-2">{{validationError('quantity', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="auth.role != 3">
                                <label for="cars">Dispensed Type:</label><br>
                                <select class="--input" v-model="formData.dispensed_type">
                                    <option :value="'barangay'">
                                        Barangay
                                    </option>

                                    <option :value="'individual'">
                                        Individual
                                    </option>
                                </select>
                                <span class="text-xs text-red-500 ml-2">{{validationError('dispensed_type', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="auth.role != 3 && formData.dispensed_type == 'barangay'">
                                <label for="cars">Barangay:</label><br>
                                <select class="--input" v-model="formData.place_id">
                                    <option v-for="place in options.places" :key="place.id"
                                        :value="place.id"
                                    >
                                        {{ place.name }}
                                    </option>
                                </select>
                                <span class="text-xs text-red-500 ml-2">{{validationError('place_id', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="formData.dispensed_type == 'individual'">
                                <label for="cars">Patients:</label><br>
                                <select class="--input" v-model="formData.patient_id">
                                    <option v-for="patient in options.patients" :key="patient.id"
                                        :value="patient.id"
                                    >
                                        {{ patient.name }}
                                    </option>
                                </select>
                                <span class="text-xs text-red-500 ml-2">{{validationError('patient_id', saveError)}} </span>
                            </div>

                            <div class="mt-3 mb-1">
                                <button class="text-center text-white" 
                                    style="height: 40px; width: 100%; border: 1px solid black; border-radius: 5px; background: #366422"
                                    @click="dispenseMedicine()"
                                >
                                    Dispense
                                </button>
                            </div>

                            <div class="w-full mt-3 mb-2" v-if="errorMessage">
                                <span class="text-lg text-red-500 ml-2">{{ errorMessage }}</span>
                            </div>
                        </div>
                    
                    </div>
                </div>

                <div id="stockModal" class="stockModal">
                    <div class="stock-modal-content flex flex-col" style="width: 30%">
                        <div class="w-full">
                            <span class="text-lg font-bold">
                                Medicine Stock
                            </span>
                            <span class="float-right cursor-pointer"
                                @click="closeStockModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="my-1 flex flex-row">
                                <div class="w-full mr-2">
                                    <label class="text-bold">Medicine:</label><br>
                                    <select class="--input" v-model="formStock.medicine_id">
                                        <option v-for="medicine in options.medicines" :key="medicine.id" :value="medicine.id">
                                            {{ medicine.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_id', saveError)}} </span>
                                </div>

                                <div class="w-full mr-2">
                                    <label class="text-bold">Category:</label><br>
                                    <select class="--input" v-model="formStock.medicine_category_id">
                                        <option v-for="category in options.categories" :key="category.id" :value="category.id">
                                            {{ category.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_category_id', saveError)}} </span>
                                </div>

                                <div class="mr-2">
                                    <label class="text-bold">Dosage:</label><br>
                                    <input type="number" class="--input" v-model="formStock.dosage">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dosage', saveError)}} </span>
                                </div>

                                <div class="w-full mr-2">
                                    <label class="text-bold">Unit:</label><br>
                                    <select class="--input" v-model="formStock.medicine_unit_id">
                                        <option v-for="unit in options.units" :key="unit.id" :value="unit.id">
                                            {{ unit.name}}
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicine_unit_id', saveError)}} </span>
                                </div>

                            </div>

                            <div class="my-1">
                                <label class="text-bold">Quantity:</label><br>
                                <input type="number" class="--input" v-model="formStock.quantity">
                                <span class="text-xs text-red-500 ml-2">{{validationError('quantity', saveError)}} </span>
                            </div>


                            <div class="mt-3 mb-2">
                                <button class="text-center text-white" 
                                    style="height: 40px; width: 100%; border: 1px solid black; border-radius: 5px; background: #366422"
                                    @click="saveStock()"
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
import axios from "axios";
import Dropdown from 'vue-simple-search-dropdown';

export default {
    props: ['auth','options'],
    components: {
        Navigation,
        Table,
        Dropdown
    },
    data(){
        return {
            activeTab: 'medicines',
            form: {
                search: null
            },
            newUser: false,
            selected: null,
            formData: {
                medicine_id: null,
                place_id: null,
                quantity : 1,
                dispensed_type: 'barangay',
                patient_id: null,
                medicine_category_id: null,
                medicine_unit_id: null,
                dosage: 1
                
            },
            formStock: {
                medicine_id: null,
                quantity : 1,
                medicine_category_id: null,
                medicine_unit_id: null,
                dosage: 1
            },
            saveError: null,
            columns: [
                'Medicine', 'Category', 'Dosage', 'Unit', 'Barangay', 'Quantity', 'Date'
            ],
            keys : [
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
            ],
            dispensed_type: 'barangay',
            barangay: null,
            medecineList: [],
            categories: [],
            units: [],
            errorMessage: null,
            search: null,
            medicineStocts: null
        }
    },
    mounted(){
        this.form.search = this.options.search
        this.formData.medicine_id = this.options.medicines.length > 0 ? this.options.medicines[0].id : null
        this.formData.place_id = this.options.places[0].id
        this.barangay = this.options.places[0].name

        this.medecineList = this.options.barangayMedicines.filter((x) => {
            return x.place_name == this.barangay;
        })

        if(this.auth.role == 3) {
            this.dispensed_type = 'individual';
            this.formData.dispensed_type = 'individual';

            this.medecineList = this.options.patientMedicines
        }

        this.formData.medicine_category_id = this.options.categories[0].id
        this.formData.medicine_unit_id = this.options.units[0].id

        this.formStock.medicine_id = this.options.medicines.length > 0 ? this.options.medicines[0].id : null
        this.formStock.medicine_category_id = this.options.categories[0].id
        this.formStock.medicine_unit_id = this.options.units[0].id

        this.medicineStocts = this.options.medicineStocts
    },
    watch: {
        search(arg) {
            if(arg) {
                this.medicineStocts = this.options.medicineStocts.filter(x => {
                    var name = x.name.toLowerCase();
                    var search = arg.toLowerCase()
                    return name.includes(search)
                });
            } else {
                this.medicineStocts = this.options.medicineStocts
            }
        },
        activeTab(arg){
            if(arg == 'stock') {
                this.columns = [
                    'Medicine', 'Category', 'Dosage', 'Unit', 'Quantity',  'Date'
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
                        label: 'deducted_quantity',
                    },
                    {
                        label: 'date',
                    },
                ]
            } else {
                if(this.dispensed_type == 'barangay') {
                    this.medecineList = this.options.barangayMedicines.filter((x) => {
                        return x.place_name == arg;
                    })

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
                } else {
                    this.medecineList = this.options.patientMedicines

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
                }
            }
        },
        'formData.medicine_id'(arg) {
            var type = null;

            if(this.auth.role == 3) { 
                type = 'barangay'
            } else {
                type = 'stock'
            }

            axios.post(this.$root.route + '/medicines/get-categories', {medicine_id: arg, type: type})
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.categories = response.data.data
                        console.log(this.categories)
					}
				})

            axios.post(this.$root.route + '/medicines/get-units', {medicine_id: arg, type: type})
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.units = response.data.data
                        console.log(this.units)
					}
				})
        },
        barangay(arg){
            this.medecineList = this.options.barangayMedicines.filter((x) => {
                return x.place_name == arg;
            })
        },
        dispensed_type(arg) {
            if(arg == 'barangay') {
                this.medecineList = this.options.barangayMedicines.filter((x) => {
                    return x.place_name == arg;
                })

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
            } else {
                this.medecineList = this.options.patientMedicines

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
            }
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
                this.$root.route + '/medicines', this.form,
                {
                    onSuccess: (res) => { 

                    },
                },
            );
        },

        dispenseMedicine(){
            if(this.formData.dispensed_type == 'barangay') {
                delete this.formData.patient_id;
            } else {
                delete this.formData.place_id;
            }

            swal({
                title: "Are you sure to dispense this medicine?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/medicines/dispense-barangay-medicine', this.formData)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                if(response.data.message == null) {
                                    this.formData = {
                                        place_id : null,
                                        quantity : null,
                                        medicine_id : null,
                                        dispensed_type: 'barangay',
                                        patient_id: null,
                                        medicine_category_id: null,
                                        medicine_unit_id: null,
                                        dosage: 1
                                    }

                                    swal({
                                        title: "Good job!",
                                        text: "You successfuly dispense this medicine",
                                        icon: "success",
                                        button: "Okay",
                                    });

                                    location.reload()
                                } else {
                                    this.errorMessage = response.data.message
                                }
                                
                            }
                        })
                }
            });

            
        },

        saveStock(){
            swal({
                title: "Are you sure to save this stock?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/medicines/save-stock', this.formStock)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                this.formStock ={
                                    medicine_id: null,
                                    quantity : 1,
                                    medicine_category_id: null,
                                    medicine_unit_id: null,
                                    dosage: 1
                                }

                                swal({
                                    title: "Good job!",
                                    text: "You successfuly dispense this stock",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                }
            });

            
        },

        openModal(){
            var modal = document.getElementById("medicineModal");

            modal.style.display = "block";
            
            this.newUser = true
        },

        closeModal(){
            var modal = document.getElementById("medicineModal");

            modal.style.display = "none";

            this.newUser = false

            this.formData = {
                place_id : null,
                quantity : null,
                medicine_id : null
            }
                

            this.saveError = null
        },

        openStockModal(){
            var modal = document.getElementById("stockModal");

            modal.style.display = "block";
            
            this.newUser = true
        },

        closeStockModal(){
            var modal = document.getElementById("stockModal");

            modal.style.display = "none";

            this.newUser = false                

            this.saveError = null
        },

        arrayUnique(arr){
            return _.uniq(arr)
        },

        selectBarangay(arg){
            this.barangay = arg.name
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
    background: #4D77FF;
    border-radius: 50px;
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
    height: 35px;
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

.wrapper {
  /* Border */
  border: 10px solid transparent;
  border-radius: 50%;
  background: 
    linear-gradient(to right, white, white), 
    linear-gradient(to right, red , blue); 
  background-clip: padding-box, border-box;
  background-origin: padding-box, border-box;
  
  /* Other styles */
  width: 50%;
  height: 200px;
  padding: 12px;
}

.medicineModal {
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
.medicine-modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 100%;
}

.stockModal {
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
.stock-modal-content {
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
