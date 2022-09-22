<template>
    <div class="w-screen h-screen flex flex-row">
        <div class="--left__panel" @mouseover="biggerWidth()" @mouseleave="smallerWidth()"
            :style="{ 'width': leftPanel}"
        >
            <ul class="pl-2 pt-10 --ul__caption text-bold w-full">
                <li class="mt-1 mb-10 cursor-pointer" v-if="hasAccess('users')"
                    @click="changeActive('/users')"
                >
                    <i class="fa-solid fa-user-group fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/users' ? '1px solid white' : 'none'}"
                    > 
                        USERS
                    </span>
                </li>

                <li class="mt-1 mb-10 cursor-pointer" v-if="hasAccess('patients')"
                    @click="changeActive('/patients')"
                >
                    <i class="fa-solid fa-users-rectangle fa-lg mx-2"></i>
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/patients' ? '1px solid white' : 'none'}"
                    > 
                        PATIENTS
                    </span>
                </li>


                <li class="my-10 cursor-pointer" v-if="hasAccess('medicines')"
                    @click="changeActive('/medicines')"
                >
                    <i class="fa-solid fa-capsules fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/medicines' ? '1px solid white' : 'none'}"
                    > 
                        MEDICINES
                    </span>
                </li>


                <li class="my-10 cursor-pointer" v-if="hasAccess('reports')"
                    @click="changeActive('/reports')"
                >
                    <i class="fa-solid fa-chart-column fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"
                        :style="{'border-bottom': active === '/reports' ? '1px solid white' : 'none'}"
                    > 
                        REPORTS
                    </span>
                </li>
            </ul>

            <ul class="--ul__caption absolute text-bold w-full"
                style="bottom: 0.2rem;"
            >
                <hr>
                <li class="my-5 px-2 cursor-pointer" @click="logout()">
                    <i class="fa-solid fa-door-open fa-lg mx-2"></i> 
                    <span v-if="isHover" class="mx-2"> LOGOUT </span>
                </li>
            </ul>
        </div>

        <div class="--right__panel"
            :style="{ 'width': rightPanel}"
        >
            <slot></slot>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
	props:['auth'],
	data(){
        return {
            leftPanel: '3%',
            rightPanel: '97%',
            isHover: false,
            active: window.location.pathname,
            tabs: []
        }
	},

    created(){
        if(this.auth.role == 1) {
            this.tabs = ['users', 'medicines', 'reports'];
        }

        if(this.auth.role == 2) {
            switch(this.auth.user_type) {
                case 'doctor':
                    this.tabs = ['users', 'patients', 'medicines', 'reports'];
                    break;
                case 'midwife':
                    this.tabs = ['patients', 'reports'];
                    break;

                case 'nurse':
                    this.tabs = ['patients'];
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

                case 'members':
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
            this.leftPanel = '3%';
            this.rightPanel = '97%';
            this.isHover = false
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
        }
	}
}

</script>

<style scoped>
.--left__panel {
    background: #003865;
    width: 15%;
}

.--right__panel {
    background: #FEFBF6;
}

.--ul__caption {
    color: white !important;
}

</style>
