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


                <div class="w-full h-full px-5 mt-10">
                    <div class="grid grid-cols-10 gap-4" v-if="activeTab == 'medicine'">
                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.medicines" :key="medicine.id" class="text-center flex justify-center items-center">
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-10 gap-4" v-if="activeTab == 'category'">
                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.categories" :key="medicine.id" class="text-center flex justify-center items-center">
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-10 gap-4" v-if="activeTab == 'unit'">
                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.units" :key="medicine.id" class="text-center flex justify-center items-center">
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-10 gap-4" v-if="activeTab == 'vaccination'">
                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" v-for="medicine in options.vaccinations" :key="medicine.id" class="text-center flex justify-center items-center">
                            {{ medicine.name }}
                        </div>

                        <div style="border: 1px solid #366422; height: 50px; word-wrap: break-word;" class="text-center flex justify-center items-center cursor-pointer" @click="openFormModal()">
                            <i class="fa-solid fa-plus"></i>
                        </div>
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
                name: null
            },
            saveError: null
        }
    },

    watch: {
        activeTab(arg) {
            
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
            axios.post(this.$root.route + '/maintenance/save-maintenance', { tab: this.activeTab, name: this.form.name })
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
</style>