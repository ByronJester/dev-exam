<template>
    <div class="w-screen h-screen flex flex-col">
        <div class="--left__panel flex flex-row" @mouseover="biggerWidth()" @mouseleave="smallerWidth()"
        >   
            <div class="w-6/12 inline-flex">
                <img src="/images/logo.jfif" style="height: 100px; width: 100px; border-radius: 50%" class="m-4"/>
                <p style="font-size: 50px; font-family: Times New Roman, Times, serif; font-weight: bold; color: white" class="mt-7">
                    Municipality of Balayan
                </p>
            </div>

            <div class="w-6/12 inline-flex justify-end items-center text-white" style="font-size: 22px">
                <div class="dropdown inline-block relative">
                    <p class="mr-2 cursor-pointer">
                        <i class="fa-solid fa-user-gear fa-lg mx-2"></i>
                        <span v-if="isHover" class="mx-2"
                            :style="{'border-bottom': active === '/change-password' ? '1px solid white' : 'none'}"
                        > 
                            ACCOUNT
                        </span>
                    </p>

                    <ul class="dropdown-menu absolute hidden pt-1">
                        <li class="cursor-pointer">
                            <a class="rounded-t bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap" @click="openModal()">Change Password</a>
                        </li>

                        <li class="cursor-pointer">
                            <a class="rounded-t bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap" @click="logout()">Logout</a>
                        </li>
                        
                    </ul>

                </div>

                <p class="mr-2 cursor-pointer" v-if="hasAccess('users')" @click="changeActive('/users')">
                    <i class="fa-solid fa-user-group fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/users' ? '1px solid white' : 'none'}"
                    > 
                        USERS
                    </span>
                </p>

                <p class="mr-2 cursor-pointer" v-if="hasAccess('maintenance')" @click="changeActive('/maintenance')">
                    <i class="fa-solid fa-prescription fa-lg mx-2"></i>
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/maintenance' ? '1px solid white' : 'none'}"
                    > 
                        MAINTENANCE
                    </span>
                </p>

                <p class="mr-2 cursor-pointer" v-if="hasAccess('trails')" @click="changeActive('/trails')">
                    <!-- <i class="fa-solid fa-prescription fa-lg mx-2"></i> -->
                    <i class="fa-solid fa-file fa-lg mx-2"></i>
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/trails' ? '1px solid white' : 'none'}"
                    > 
                        AUDIT TRAILS
                    </span>
                </p>

                <p class="mr-2 cursor-pointer" v-if="hasAccess('patients')" @click="changeActive('/patients')">
                    <i class="fa-solid fa-users-rectangle fa-lg mx-2"></i>
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/patients' || (active.includes('patients') && active.includes('false')) || active.includes('history') ? '1px solid white' : 'none'}"
                    > 
                        PATIENTS
                    </span>
                </p>

                <p class="mr-2 cursor-pointer" v-if="hasAccess('medicines')" @click="changeActive('/medicines')">
                    <i class="fa-solid fa-capsules fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/medicines' ? '1px solid white' : 'none'}"
                    > 
                        MEDICINES
                    </span>
                </p>

                <p class="mr-2 cursor-pointer" v-if="hasAccess('reports')" @click="changeActive('/reports')">
                    <i class="fa-solid fa-chart-column fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/reports' || (active.includes('patients') && active.includes('true')) ? '1px solid white' : 'none'}"
                    > 
                        REPORTS
                    </span>
                </p>

            </div>
        </div>

        <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content flex flex-col" style="width: 20%">
                <div class="w-full">
                    <span class="text-lg font-bold">
                        Change Password
                    </span>
                    <span class="float-right cursor-pointer"
                        @click="closeModal()"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                </div>

                <div class="w-full flex flex-col">
                    <div class="w-full py-2">
                        <input type="password" style="width: 100%; height: 40px; border: 1px solid black" class="text-center"
                            placeholder="Current Password" v-model="formData.current_password"
                        >
                        <span class="text-xs text-red-500">{{validationError('current_password', saveError)}} </span>
                    </div>

                    <div class="w-full py-2">
                        <input type="password" style="width: 100%; height: 40px; border: 1px solid black" class="text-center"
                            placeholder="New Password" v-model="formData.new_password"
                        >
                        <span class="text-xs text-red-500">{{validationError('new_password', saveError)}} </span>
                    </div>

                    <div class="w-full py-2">
                        <input type="password" style="width: 100%; height: 40px; border: 1px solid black" class="text-center"
                            placeholder="Confirm Password" v-model="formData.confirm_password"
                        >
                        <span class="text-xs text-red-500">{{validationError('confirm_password', saveError)}} </span>
                    </div>

                    <div class="w-full py-2">
                        <button style="border: 1px solid black; background: #000000; color: #ffffff; height: 50px; width: 100%" class="text-center"
                            @click="changePassword()"
                        >
                            Change Password
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="w-full" style="min-height: 87vh; height: 100%" 
        >   
            <slot></slot>
        </div>

        
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import axios from "axios";

export default {
	props:['auth'],
	data(){
        return {
            leftPanel: '9%',
            rightPanel: '91%',
            isHover: true,
            active: window.location.pathname,
            tabs: [],
            formData: {
                current_password: null,
                new_password: null,
                confirm_password: null
            },
            saveError: null
        }
	},

    created(){
        if(this.auth.role == 1) {
            this.tabs = ['users', 'maintenance', 'trails'];
        }

        if(this.auth.role == 2) {
            switch(this.auth.user_type) {
                case 'doctor':
                    this.tabs = ['users', 'patients', 'reports'];
                    break;
                case 'pharmacist':
                    this.tabs = ['medicines', 'reports'];
                    break;
                case 'midwife':
                    this.tabs = ['patients', 'reports'];
                    break;

                case 'nurse':
                    this.tabs = ['patients', 'reports'];
                    break;
            }
        }

        if(this.auth.role == 3) {
            switch(this.auth.user_type) {
                case 'leader':
                    this.tabs = ['users', 'patients', 'medicines', 'reports'];
                    break;
                case 'midwife':
                    this.tabs = ['patients', 'reports'];
                    break;

                case 'member':
                    this.tabs = ['patients', 'reports'];
                    break;
            }
        }
    },

	methods: {
        biggerWidth() {
            this.leftPanel = '9%';
            this.rightPanel = '91%';
            this.isHover = true
        },

        smallerWidth(){
            this.leftPanel = '9%';
            this.rightPanel = '91%';
            this.isHover = true
        },

        logout(){
            Inertia.post(this.$root.route + "/users/logout", {},
			{
				onSuccess: (res) => {
				},
				orError: (err) => {
				}
			});
        },

        hasAccess(arg){
            return this.tabs.includes(arg)
        },

        changeActive(arg){
            Inertia.get(
                this.$root.route + arg,
                {
                    onSuccess: () => { },
                },
            );
        },

        openModal(){
            var modal = document.getElementById("myModal");

            modal.style.display = "block";
        },

        closeModal(){
            var modal = document.getElementById("myModal");

            modal.style.display = "none";
        },

        changePassword(){
            axios.post(this.$root.route + "/users/change-password", this.formData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.formData = {
                            current_password: null,
                            new_password: null,
                            confirm_password: null
                        }

                        this.closeModal()
                        this.saveError = null

                        alert("Successfully changed password");
					}
				})
        }

	}
}

</script>

<style scoped>
.--left__panel {
    background: #366422;
    width: 100%;
    height: 13vh;
}

.--right__panel {
    background: #FEFBF6;
}

.--ul__caption {
    color: white !important;
}

.modal {
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
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
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

@font-face {
  font-family: Kawaru;
  src: url('/fonts/kawaru/kawaru.woff');
}

.dropdown:hover .dropdown-menu {
    display: block;
    width: 200px;
    text-align: center;
    color: #000000;
    font-size: 15px;
}

</style>
