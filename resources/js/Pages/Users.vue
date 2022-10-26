<template>
	<div class="">
        <Navigation :auth="auth">
            <div class="px-4 pt-12">
                
                <div class="flex flex-row mb-5">
                    <div class="w-10/12">
                        <input type="text" class="--search pl-5"
                            v-model="form.search" @input="initiateSearch()"
                            placeholder="Search...."
                        >
                    </div>

                    <div class="w-2/12">
                        <button class="text-black float-right p-2"
                            style="border: 1px solid black; border-radius: 5px"
                            @click="newUser = true"
                        >
                            <i class="fa-solid fa-person-circle-plus fa-2xl"></i>
                        </button>
                    </div>
                </div>

                <div class="flex flex-row">
                    <div class="grid grid-cols-4 gap-2 w-full h-full"
                        :class="{'w-7/12': !!newUser}"
                    >
                        <div v-for="user in users" :key="user.id" 
                            class="--user"
                            style="background-color: #EFDAD7"
                        >   
                            <div class="flex flex-row">
                                <div class="w-2/5 p-2">
                                    <img src="/images/dp.jpg"
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

                                    <div class="mt-2 ml-3"> 
                                        <Toggle :value="user.is_active" :url="'/users/deactivate-reactivate'" :id="user.id" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-4/12" v-if="newUser"
                        style="border: 1px solid #22577E; border-radius: 5px"
                    >
                        <div class="w-full text-center text-white"
                            style="border-bottom: 1px solid #22577E; background-color: #003865"
                        >
                            <span class="font-bold text-lg">
                                Create User Account
                            </span>
                            <i class="fa-solid fa-xmark fa-md mr-2 mt-1 cursor-pointer float-right"
                                @click="newUser = false"
                            ></i>
                        </div>

                        <div class="w-full p-2 flex flex-col"
                            style="background-color: #EFDAD7"
                        >
                            <div class="my-1">
                                <label class="text-bold">First Name:</label><br>
                                <input type="text" class="--input" v-model="formData.first_name">
                                <span class="text-xs text-red-500 ml-2">{{validationError('first_name', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Middle Name:</label><br>
                                <input type="text" class="--input" v-model="formData.middle_name">
                                <span class="text-xs text-red-500 ml-2">{{validationError('middle_name', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Last Name:</label><br>
                                <input type="text" class="--input" v-model="formData.last_name">
                                <span class="text-xs text-red-500 ml-2">{{validationError('last_name', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Contact No.:</label><br>
                                <input type="text" class="--input" v-model="formData.phone"
                                    placeholder="639xxxxxxxxx"
                                >
                                <span class="text-xs text-red-500 ml-2">{{validationError('phone', saveError)}} </span>
                            </div>

                            <div class="my-1">
                                <label class="text-bold">Email:</label><br>
                                <input type="text" class="--input" v-model="formData.email"
                                    placeholder="example@email.com"
                                >
                                <span class="text-xs text-red-500 ml-2">{{validationError('email', saveError)}} </span>
                            </div>

                            <div class="my-1" v-if="auth.role == 1">
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

                            <div class="my-1">
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

                            <div class="mt-3 mb-2">
                                <button class="w-full py-2 px-4 text-white font-bold" 
                                    style="border-radius: 50px; background-color: #4D77FF"
                                    @click="createUser()"
                                >
                                    Create Account
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
                first_name : null,
                middle_name : null,
                last_name : null,
                phone : null,
                email : null,
                user_type : null,
                work_address: 0
            },
            saveError: null
        }
    },

    mounted(){
        this.users = this.options.users
        this.form.search = this.options.search

        console.log(this.options)

        if(this.auth.role == 1) {
            this.userType = [
                {
                    label: 'BHW - Leader',
                    value: 'leader'
                }
            ]
        }

        if(this.auth.role == 2) {
            this.userType = [
                {
                    label: 'RHU - Doctor',
                    value: 'doctor'
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
                    label: 'BHW - Leader',
                    value: 'leader'
                },

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

        createUser(){
            if(this.auth.role == 3) {
                this.formData.work_address = this.auth.work_address
            }

            Inertia.post(this.$root.route + '/users/create-account', this.formData,
            {
                onSuccess: (res) => {
                    this.formData = {
                        first_name : null,
                        middle_name : null,
                        last_name : null,
                        phone : null,
                        email : null,
                        user_type : null,
                        work_address: 0
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



</style>