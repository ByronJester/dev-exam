<template>
    <div class="w-full h-screen">
        <Navigation :auth="auth">
            <div class="px-4 pt-12">
                <!-- <div class="flex flex-row text-center font-bold text-lg">
                    <div class="w-full">
                        <span class="cursor-pointer"
                            :style="{'border-bottom': activeTab == 'medicines' ? '1px solid black' : 'none'}"
                            @click="activeTab = 'medicines'"
                        >
                            Medicines
                        </span>
                    </div>

                    <div class="w-full">
                        <span class="cursor-pointer"
                            @click="activeTab = 'statistics'"
                            :style="{'border-bottom': activeTab == 'statistics' ? '1px solid black' : 'none'}"
                        >
                            Statistics
                        </span>
                    </div>
                </div> -->

                <div v-if="activeTab == 'medicines'" class="w-full px-12">
                    <div class="text-2xl font-bold text-blue-500 w-full"
                        style="border-bottom: 1px solid black"
                    >
                        Medicine Report
                    </div>

                    <div class="flex flex-row mb-5 mt-10">
                        <div class="w-10/12">
                            <input type="text" class="--search pl-5"
                                v-model="form.search" @input="initiateSearch()"
                                placeholder="Search...."
                            >
                        </div>

                        <div class="w-2/12" v-if="auth.role != 3">
                            <button class="text-black float-right p-2"
                                style="border: 1px solid black; border-radius: 5px"
                                @click="newUser = true"
                            >
                                <i class="fa-solid fa-house-chimney-medical fa-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-row">
                        <div class="w-9/12 h-full mr-2">
                            <Table :columns="columns"  :rows="medicines" :keys="keys"/>
                        </div>

                        <div class="w-3/12" v-if="!newUser"
                            style="border: 1px solid #22577E; border-radius: 5px"
                        >
                            <div class="w-full flex align-center justify-center">
                                <div class="text-6xl w-6/12 wrapper text-center my-5">
                                    <div class="mt-10"> {{ options.medicineCount }} </div>
                                </div>
                            </div>

                            <div class="w-full text-center text-xl font-bold">
                                Total medicine for {{ options.month }} {{ options.year }} 
                            </div>

                            <div class="w-full flex align-center justify-center">
                                <div class="text-6xl w-6/12 wrapper text-center my-5">
                                    <div class="mt-10"> {{ options.medicineDispensed }} </div>
                                </div>
                            </div>

                            <div class="w-full text-center text-xl font-bold mb-5">
                                Medicine dispensed for {{ options.month }} {{ options.year }} 
                            </div>

                        </div>

                        <div class="w-3/12" v-if="newUser"
                            style="border: 1px solid #22577E; border-radius: 5px"
                        >
                            <div class="w-full text-center text-white"
                                style="border-bottom: 1px solid #22577E; background-color: #003865"
                            >
                                <span class="font-bold text-lg">
                                    New Medicine
                                </span>
                                <i class="fa-solid fa-xmark fa-md mr-2 mt-1 cursor-pointer float-right"
                                    @click="newUser = false"
                                ></i>
                            </div>

                            <div class="w-full p-2 flex flex-col"
                                style="background-color: #EFDAD7"
                            >
                                <div class="my-1">
                                    <label class="text-bold">Name:</label><br>
                                    <input type="text" class="--input" v-model="formData.name">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('name', saveError)}} </span>
                                </div>

                                <div class="my-1">
                                    <label class="text-bold">Quantity.:</label><br>
                                    <input type="number" class="--input" v-model="formData.quantity">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('quantity', saveError)}} </span>
                                </div>

                                <div class="my-1">
                                    <label class="text-bold">Date:</label><br>
                                    <input type="date" class="--input" v-model="formData.date">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="my-1" v-if="auth.role != 3">
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

                                <div class="mt-3 mb-2">
                                    <button class="w-full py-2 px-4 text-white font-bold" 
                                        style="border-radius: 50px; background-color: #4D77FF"
                                        @click="createMedicine()"
                                    >
                                        Create
                                    </button>
                                </div>
                                
                            </div>
                        </div>

                    </div>

                </div>

                <!-- <div v-if="activeTab == 'statistics'" class="w-full pt-20 px-12">
                    <div class="text-2xl font-bold text-blue-500 w-full"
                        style="border-bottom: 1px solid black"
                    >
                        Statistics
                    </div>
                </div> -->

            </div>
        </Navigation>
    </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import Navigation from "../Layouts/Sidebar";
import Table from "../Components/Table";

export default {
    props: ['auth','options'],
    components: {
        Navigation,
        Table
    },
    data(){
        return {
            activeTab: 'medicines',
            medicines: [],
            form: {
                search: null
            },
            newUser: false,
            selected: null,
            formData: {
                place_id: null,
                name : null,
                quantity : 0
            },
            saveError: null,
            columns: [
                'Name', 'Barangay', 'Quantity', 'Dispensed', 'Date'
            ],
            keys : [
                {
                    label: 'name',
                },
                {
                    label: 'barangay',
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
            ],
        }
    },
    mounted(){
        this.medicines = this.options.medicines
        this.form.search = this.options.search
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
                        console.log(res)

                    },
                },
            );
        },

        createMedicine(){
            Inertia.post(this.$root.route + '/medicines/create-medicine', this.formData,
            {
                onSuccess: (res) => {
                    this.formData = {
                        place_id : null,
                        name : null,
                        quantity : null
                    }

                    location.reload()
                },
                onError: (err) => {
                    this.saveError = err
                }
            });
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
</style>
