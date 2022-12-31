<template>
    <div class="w-full h-screen">
        <Navigation :auth="auth">
            <div class="w-full flex flex-col">
                <div class="w-full flex flex-row mt-8" style="height: 5vh;">
                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        @click="activeTab = 'medicine'"
                        :class="{'--bg_gray': activeTab == 'medicine'}"
                    >
                        Medicine
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        :class="{'--bg_gray': activeTab == 'category'}"
                        @click="activeTab = 'category'"
                    >
                        Medicine Category
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        :class="{'--bg_gray': activeTab == 'unit'}"
                        @click="activeTab = 'unit'"
                    >
                        Medicine Unit
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        :class="{'--bg_gray': activeTab == 'vaccination'}"
                        @click="activeTab = 'vaccination'"
                    >
                        Vaccination
                    </div>
                </div>

                <div class="w-full mt-5">
                    <input type="text" class="--search pl-5 ml-5"
                        v-model="search"
                        placeholder="Search...."
                    >

                    <span class="float-right mr-5 cursor-pointer" style="border: 1px solid black" @click="openFormModal()">
                        <i class="fa-solid fa-plus px-2 py-2"></i>
                    </span>
                </div>


                <div class="w-full h-full px-5 mt-5">
                    <div style="width: 100%" v-if="activeTab == 'medicine'">
                        <!-- <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.medicines" :key="medicine.id" class="text-center flex justify-center items-center cursor-pointer"
                            @click="selectItem(medicine, 'medicine')"
                        >
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div> -->

                        <table class="w-full">
                            <tr class="text-center">
                                <th v-for="column in columns" :key="column">
                                    {{ column }}
                                </th>
                            </tr>

                            <tr class="text-center"
                                v-for="(l, index) in rows" :key="index"
                            >
                                <td v-for="(k, i) in keys" :key="i" class="cursor-pointer"
                                    :class="{'--active__color': !!selected && selected.id == l.id }"
                                    @click="selectItem(l, 'medicine')"
                                >
                                    <span>{{ rows[index][k.label] }}</span> 
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="width: 100%" v-if="activeTab == 'category'">
                        <!-- <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.categories" :key="medicine.id" class="text-center flex justify-center items-center cursor-pointer"
                            @click="selectItem(medicine, 'category')"
                        >
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div> -->
                        <table class="w-full">
                            <tr class="text-center">
                                <th v-for="column in columns" :key="column">
                                    {{ column }}
                                </th>
                            </tr>

                            <tr class="text-center"
                                v-for="(l, index) in rows" :key="index"
                            >
                                <td v-for="(k, i) in keys" :key="i" class="cursor-pointer"
                                    :class="{'--active__color': !!selected && selected.id == l.id }"
                                    @click="selectItem(l, 'category')"
                                >
                                    <span>{{ rows[index][k.label] }}</span> 
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="width: 100%" v-if="activeTab == 'unit'">
                        <!-- <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.units" :key="medicine.id" class="text-center flex justify-center items-center cursor-pointer"
                            @click="selectItem(medicine, 'unit')"
                        >
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div> -->
                        <table class="w-full">
                            <tr class="text-center">
                                <th v-for="column in columns" :key="column">
                                    {{ column }}
                                </th>
                            </tr>

                            <tr class="text-center"
                                v-for="(l, index) in rows" :key="index"
                            >
                                <td v-for="(k, i) in keys" :key="i" class="cursor-pointer"
                                    :class="{'--active__color': !!selected && selected.id == l.id }"
                                    @click="selectItem(l, 'unit')"
                                >
                                    <span>{{ rows[index][k.label] }}</span> 
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="width: 100%" v-if="activeTab == 'vaccination'">
                        <!-- <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.vaccinations" :key="medicine.id" class="text-center flex justify-center items-center cursor-pointer"
                            @click="selectItem(medicine, 'vaccination')"
                        >
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div> -->
                        <table class="w-full">
                            <tr class="text-center">
                                <th v-for="column in columns" :key="column">
                                    {{ column }}
                                </th>
                            </tr>

                            <tr class="text-center"
                                v-for="(l, index) in rows" :key="index"
                            >
                                <td v-for="(k, i) in keys" :key="i" class="cursor-pointer"
                                    :class="{'--active__color': !!selected && selected.id == l.id }"
                                    @click="selectItem(l, 'vaccination')"
                                >
                                    <span>{{ rows[index][k.label] }}</span> 
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="formModal" class="formModal">
                    <div class="form-modal-content flex flex-col" style="width: 20%">
                        <div class="w-full">
                            <span class="text-lg font-bold" style="text-transform: uppercase">
                                New {{activeTab}}
                            </span>
                            <span class="float-right cursor-pointer"
                                @click="closeFormModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-col mt-5">
                            <div class="my-1">
                                <label class="text-bold">Name:</label><br>
                                <input type="text" class="--input" v-model="form.name">
                                <span class="text-xs text-red-500 ml-2">{{validationError('name', saveError)}} </span>
                            </div>

                            <div class="mt-3 mb-2">
                                <button class="w-full py-2 px-4 text-white font-bold" 
                                    style="border-radius: 10px; background-color: #366422"
                                    @click="save()"
                                >
                                    Submit
                                </button>
                            </div>
                        </div>
                    
                    </div>
                </div>

                <div id="editModal" class="editModal">
                    <div class="edit-modal-content flex flex-col" style="width: 20%">
                        <div class="w-full">
                            <span class="text-lg font-bold" style="text-transform: uppercase">
                                Edit {{ activeTab }}
                            </span>
                            <span class="float-right cursor-pointer"
                                @click="closeEditModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-col mt-5" v-if="formSelected.name">
                            <div class="my-1">
                                <label class="text-bold">Name:</label><br>
                                <input type="text" class="--input" v-model="formSelected.name">
                                <span class="text-xs text-red-500 ml-2">{{validationError('name', saveError)}} </span>
                            </div>

                            <div class="mt-3 mb-2">
                                <button class="w-full py-2 px-4 text-white font-bold" 
                                    style="border-radius: 10px; background-color: #366422"
                                    @click="edit()"
                                >
                                    Submit
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
            activeTab: 'medicine',
            form: {
                id: null,
                name: null
            },
            saveError: null,
            formSelected : {
                id: null,
                name: null,
                type: null
            },
            columns: ['Name'],
            keys: [
                {
                    label: 'name'
                }
            ],
            search: null
        }
    },

    watch: {
        activeTab(arg) {
            if(arg == 'medicine') {
                this.rows = this.options.medicines
            }

            if(arg == 'category') {
                this.rows = this.options.categories
            }

            if(arg == 'unit') {
                this.rows = this.options.units
            }

            if(arg == 'vaccination') {
                this.rows = this.options.vaccinations
            }
        },
        search(arg) {
            if(this.activeTab == 'medicine') {
                if(arg) {
                    this.rows = this.options.medicines.filter(x => {
                        var name = x.name.toLowerCase();
                        var search = arg.toLowerCase()
                        return name.includes(search)
                    });
                } else {
                    this.rows = this.options.medicines
                }
            }

            if(this.activeTab == 'category') {
                if(arg) {
                    this.rows = this.options.categories.filter(x => {
                        var name = x.name.toLowerCase();
                        var search = arg.toLowerCase()
                        return name.includes(search)
                    });
                } else {
                    this.rows = this.options.categories
                }
            }

            if(this.activeTab == 'unit') {
                if(arg) {
                    this.rows = this.options.units.filter(x => {
                        var name = x.name.toLowerCase();
                        var search = arg.toLowerCase()
                        return name.includes(search)
                    });
                } else {
                    this.rows = this.options.units
                }
            }

            if(this.activeTab == 'vaccination') {
                if(arg) {
                    this.rows = this.options.vaccinations.filter(x => {
                        var name = x.name.toLowerCase();
                        var search = arg.toLowerCase()
                        return name.includes(search)
                    });
                } else {
                    this.rows = this.options.vaccinations
                }
            }
        }
    },

    mounted(){
        this.rows = this.options.medicines
    },

    methods: {
        openFormModal(){
            var modal = document.getElementById("formModal");

            modal.style.display = "block";

            this.form.name = null
            this.saveError = null
        },

        closeFormModal(){
            var modal = document.getElementById("formModal");

            modal.style.display = "none";

            this.form.name = null
            this.saveError = null
        },

        save() {
            swal({
                title: "Are you sure to save this maintenance?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/maintenance/save-maintenance', { tab: this.activeTab, name: this.form.name })
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this maintenance",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                }
            });
        },

        openEditModal(){
            var modal = document.getElementById("editModal");

            modal.style.display = "block";
        },

        closeEditModal(){
            var modal = document.getElementById("editModal");

            modal.style.display = "none";
        },

        selectItem(item, type) {
            this.formSelected.id = item.id
            this.formSelected.name = item.name
            this.formSelected.type = type

            this.openEditModal()
        },

        edit(){
            swal({
                title: "Are you sure to save this maintenance?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/maintenance/save-maintenance', { tab: this.activeTab, id: this.formSelected.id, name: this.formSelected.name })
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this maintenance",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                }
            });
            
        }
    }
}

</script>

<style scoped>
.--bg_gray {
    background: #C0C0C0;
}

.formModal {
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
.form-modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 100%;
}

.editModal {
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
.edit-modal-content {
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

.--search {
    width: 20%;
    height: 35px;
    border: 1px solid black;
    border-radius: 40px;
}
</style>