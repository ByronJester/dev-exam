<template>
	<div class="w-screen h-screen --panel">
		<div class="--login__register">
			<div class="py-5 px-10">
				<div class="w-full flex justify-center items-center mb-5" style="font-size: 80px">
					<i class="fa-solid fa-user"></i>
				</div>

  				<input type="text" class="w-full  my-2 --login__register--input text-center"
					placeholder="Email" v-model="formData.email"
					@keyup.enter="login()"
				>

  				<input type="password" class="w-full mt-2 --login__register--input text-center"
					:class="{'mb-2' : !message}"
					placeholder="Password" v-model="formData.password"
					@keyup.enter="login()"
				>

				<span class="text-red-500 text-xs ml-2" v-if="message">
					{{ message }}
				</span>

				<button class="w-full  my-2 --login__register--button text-center"
					@click="login()"
					:disabled="disableButton()"
					:class="{ 'cursor-not-allowed' : disableButton() }"
				>
					Login
				</button>
			</div>

		</div>

	</div>

</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
	props:['message'],
	data(){
		return {
			formData : {
				email: null,
				password: null 
			}
		}
	},

	methods: {
		login() {
			Inertia.post(this.$root.route + "/users/login", this.formData,
			{
				onSuccess: (res) => {
				},
				orError: (err) => {
				}
			});
		},

		disableButton(){
			if(!this.formData.email){
				return true;
			}

			if(!this.formData.password){
				return true;
			}

			return false;
		}
	}
}

</script>

<style scoped>
.--panel {
	background-image: url('/images/bg.webp');
	background-repeat: no-repeat;
 	background-size: 100vw 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
}

.--login__register {
	width: auto;
	height: auto;
	background: #366422;
	border-radius: 5px;
	position: relative;
	top: 1.5rem;
	left: 1.5rem;
}

.--login__register--input {
	height: 40px;
	border-radius: 10px;
}

.--login__register--button {
	height: 40px;
	border-radius: 30px;
	background: #54BAB9;
	color: white
}


</style>