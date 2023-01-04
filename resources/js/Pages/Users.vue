<template>
	<div class="">
        <Navigation :auth="auth">
            <div class="px-4 pt-12">
                
                <div class="flex flex-row mb-5 w-full" v-if="!newUser">
                    <div class="w-full">
                        <input type="text" class="--search pl-5"
                            v-model="form.search" @input="initiateSearch()"
                            placeholder="Search...."
                        >
                    </div>

                    <div class="w-full">
                        <button class="text-black float-right p-2"
                            style="border: 1px solid black; border-radius: 5px"
                            @click="openModal()"
                        >
                            <i class="fa-solid fa-person-circle-plus fa-2xl"></i>
                        </button>
                    </div>
                </div>

                <div class="flex flex-row" v-if="!newUser">
                    <div class="grid grid-cols-4 gap-2 w-full h-full"
                        :class="{'w-6/12': !!newUser}"
                    >
                        <div v-for="user in users" :key="user.id" 
                            class="--user"
                            style="background-color: #EFDAD7"
                        >   
                            <div class="flex flex-row">
                                <div class="w-2/5 p-2">
                                    <img :src="user.image ? '/images/uploads/' + user.image : '/images/dp.jpg'"
                                        class="--display__picture"
                                    />
                                </div>

                                <div class="w-3/5">
                                    <div class="mt-4 ml-3"> 
                                        <span class="text-md font-bold" 
                                        :class="{'text-lg' : !newUser}">{{ user.first_name }} {{ user.last_name }}
                                        </span>   
                                    </div>

                                    <div class="ml-3"> 
                                        <span class="text-xs font-regular"
                                            :class="{'text-sm' : !newUser}"
                                        >{{ getUserType(user) }}  
                                        </span>

                                        

                                        <span class="text-xs font-regular" :class="{'text-sm' : !newUser}" v-if="auth.role == 1 && user.user_type == 'leader'">
                                            ({{ user.place }})
                                        </span>
                                    </div>

                                    <div class="ml-3"> 
                                        <span class="text-xs font-regular" :class="{'text-sm' : !newUser}">{{ user.email }}  </span>
                                    </div>

                                    <div class="ml-3"> 
                                        <span class="text-xs font-regular" :class="{'text-sm' : !newUser}">{{ user.phone }}  </span>
                                    </div>

                                    <div class="mt-2 ml-3 inline-flex"> 
                                        <Toggle :value="user.is_active" :url="'/users/deactivate-reactivate'" :id="user.id" />

                                        <button class="--view__profile mx-1" @click="editProfile(user)">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="userModal" class="userModal">
                    <div class="user-modal-content flex flex-col" style="width: 30%">
                        <div class="w-full">
                            <span class="text-lg font-bold">
                                Update User
                            </span>
                            <span class="float-right cursor-pointer"
                                @click="closeModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="my-1">
                                <label class="text-bold">First Name:</label><br>
                                <input type="text" class="--input" v-model="formData.first_name" style="text-transform: capitalize;">
                                <span class="text-xs text-red-500 ml-2">{{validationError('first_name', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Middle Name:</label><br>
                                <input type="text" class="--input" v-model="formData.middle_name" style="text-transform: capitalize;">
                                <span class="text-xs text-red-500 ml-2">{{validationError('middle_name', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Last Name:</label><br>
                                <input type="text" class="--input" v-model="formData.last_name" style="text-transform: capitalize;">
                                <span class="text-xs text-red-500 ml-2">{{validationError('last_name', saveError)}} </span>
                            </div>

                            <div class="my-1 w-full flex flex-row">
                                <div class="w-full">
                                    <label class="text-bold">Picture:</label><br>
                                    <input type="file" class="w-full" accept="image/png, image/jpeg" @change="imageChange($event)">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('image', saveError)}} </span>
                                </div>

                                <div class="w-full">
                                    <label class="text-bold">Contact No.:</label><br>
                                    <input type="text" class="--input" v-model="formData.phone"
                                        placeholder="09xxxxxxxxx"
                                    >
                                    <span class="text-xs text-red-500 ml-2">{{validationError('phone', saveError)}} </span>
                                </div>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Username:</label><br>
                                <input type="text" class="--input" v-model="formData.username"
                                    placeholder="Username"
                                >
                                <span class="text-xs text-red-500 ml-2">{{validationError('username', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="!isEdit">
                                <label for="cars">User Type:</label><br>
                                <select class="--input" v-model="formData.user_type">
                                    <option v-for="type in userType" :key="type.value"
                                        :value="type.value"
                                    >
                                        {{ type.label }}
                                    </option>
                                </select>
                                <span class="text-xs text-red-500 ml-2">{{validationError('user_type', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="auth.role == 1 && formData.user_type != 'doctor' && !isEdit">
                                <label for="cars">Barangay:</label><br>
                                <select class="--input" v-model="formData.work_address">
                                    <option v-for="place in options.places" :key="place.id"
                                        :value="place.id"
                                    >
                                        {{ place.name }}
                                    </option>
                                </select>
                                <span class="text-xs text-red-500 ml-2">{{validationError('work_address', saveError)}} </span>
                            </div>

                            <div class="mt-3 mb-2">
                                <button class="w-full py-2 px-4 text-white font-bold" 
                                    style="border-radius: 50px; background-color: #4D77FF"
                                    @click="createUser()"
                                >
                                    Save Account
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

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle
    },

    data(){
        return {
            users: [],
            form: {
                search: null
            },
            newUser: false,
            viewUser: false,
            selected: null,
            userType: [],
            formData: {
                id: '',
                first_name : '',
                middle_name : '',
                last_name : '',
                phone : '',
                username : '',
                user_type : '',
                work_address: ''
            },
            saveError: null,
            isEdit: false,
            form_data: new FormData(),
        }
    },

    watch: {
        'formData.user_type'(arg){
            this.formData.work_address = null
        }
    },

    mounted(){
        this.users = this.options.users
        this.form.search = this.options.search

        if(this.auth.role == 1) {
            this.formData.user_type = 'doctor'

            this.userType = [
                {
                    label: 'Doctor',
                    value: 'doctor'
                },
                {
                    label: 'BHW - Leader',
                    value: 'leader'
                },
            ]
        }

        if(this.auth.role == 2) {
            this.userType = [
                {
                    label: 'Pharmacist',
                    value: 'pharmacist'
                },

                {
                    label: 'Chief Midwife',
                    value: 'midwife'
                },

                {
                    label: 'Nurse',
                    value: 'nurse'
                },
            ]
        }

        if(this.auth.role == 3) {
            this.userType = [
                {
                    label: 'Barangay Midwife',
                    value: 'midwife'
                },

                {
                    label: 'BHW - Member',
                    value: 'member'
                },
            ]
        }
    },

    methods: {
        imageChange(e) {
	      	const image = e.target.files[0];

            const reader = new FileReader();

            reader.readAsDataURL(image);

            reader.onload = e =>{
            }

            this.form_data.append('image', image)
	      	
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
        
        initiateSearch() {
            var self = this

            self.viewUser = false;
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
                this.$root.route + '/users', this.form,
                {
                    onSuccess: () => { },
                },
            );
        },

        viewProfile(user) {
            this.selected = user
            this.viewUser = true
        },

        editProfile(user){
            this.formData = {
                id: '',
                first_name : '',
                middle_name : '',
                last_name : '',
                phone : '',
                username : '',
            }

            this.formData = {
                id: user.id,
                first_name : user.first_name,
                middle_name : user.middle_name,
                last_name : user.last_name,
                phone : user.phone,
                username : user.username,
            }

            this.isEdit = true

            this.form_data.append('id', this.formData.id);

            this.openModal()

        },

        createUser(){
            if(this.auth.role == 3) {
                this.formData.work_address = this.auth.work_address
            }

            this.form_data.append('first_name', this.formData.first_name);
			this.form_data.append('middle_name', this.formData.middle_name);
			this.form_data.append('last_name', this.formData.last_name);
            this.form_data.append('phone', this.formData.phone);
            this.form_data.append('username', this.formData.username);
            this.form_data.append('user_type', this.formData.user_type);
            this.form_data.append('work_address', this.formData.work_address);

            if((this.formData.user_type == 'doctor' && this.auth.role == 1) || this.auth.role == 2) {
                delete this.formData.work_address;
                this.form_data.delete('work_address');
            }

            if(!!this.isEdit) {
                delete this.formData.user_type;
                delete this.formData.work_address;
                this.form_data.delete('user_type');
                this.form_data.delete('work_address');
            }
            
            swal({
                title: "Are you sure to save this user account?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    Inertia.post(this.$root.route + '/users/create-account', this.form_data,
                    {
                        onSuccess: (res) => {
                            // this.formData = {
                            //     first_name : null,
                            //     middle_name : null,
                            //     last_name : null,
                            //     phone : null,
                            //     email : null,
                            //     user_type : null,
                            //     work_address: null
                            // }

                            swal({
                                title: "Successful",
                                text: "You successfuly save this user account",
                                icon: "success",
                                button: "Okay",
                            });

                            location.reload()
                        },
                        onError: (err) => {
                            this.saveError = err
                            console.log(err)
                        }
                    });
                } 
            });
        },

        openModal(){
            var modal = document.getElementById("userModal");

            modal.style.display = "block";
            
            this.newUser = true
        },

        closeModal(){
            var modal = document.getElementById("userModal");

            modal.style.display = "none";

            this.newUser = false

            this.formData = {
                id: '',
                first_name : '',
                middle_name : '',
                last_name : '',
                phone : '',
                username : '',
                user_type : '',
                work_address: ''
            }

            this.saveError = null

            this.isEdit = false
        },
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
}

.--display__picture {
    width: 100%; 
    height: 150px; 
    border: 2px solid black; 
    border-radius: 20px;
}

.--search {
    width: 40%;
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

.userModal {
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
.user-modal-content {
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

.--view__profile {
    background: #366422;
    border-radius: 5px;
    color: white;
    padding: 5px 15px 5px 15px;
    font-size: 0.750em;
}

</style>