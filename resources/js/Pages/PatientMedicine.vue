<template>
    <Navigation :auth="auth">
        <div class="px-4 pt-12">
            <div class="w-full">
                <span class="text-2xl">
                    <i class="fa-solid fa-arrow-left mr-2 cursor-pointer" @click="back()"></i> Patient Medicines
                </span>

                <span class="text-2xl float-right font-bold">
                    {{ patient.name }} ({{ patient.place.name }})
                </span>
            </div>

            <div class="w-full flex flex-row mt-10">

                <div style="width: 50%">
                    <Table :columns="columns" :rows="options.patientMedicines" :keys="keys" :selected.sync="selected"/>
                </div>

                <div style="width: 50%">
                    <div class="float-right flex flex-col"
                        style="border: 1px solid black; border-radius: 5px; width: 50%"
                    >
                        <div class="w-full text-2xl font-bold pl-4 pt-4">
                            Dispense Medicine
                        </div>

                        <div class="w-full px-4 py-3">
                            <select class="text-center" v-model="form.medicine_id"
                                style="height: 40px; width: 100%; border: 1px solid black; border-radius: 5px;"
                            >
                                <option :value="med.id" v-for="med in options.availableMedicines" :key="med.id">
                                    {{ med.name }}
                                </option>
                            </select>
                            <span class="text-xs text-red-500 ml-2">{{validationError('medicine_id', saveError)}} </span>
                        </div>

                        <div class="w-full px-4 py-3">
                            <input type="text" placeholder="Quantity" class="text-center" v-model="form.quantity"
                                style="height: 40px; width: 100%; border: 1px solid black; border-radius: 5px;"
                            >
                            <span class="text-xs text-red-500 ml-2">{{validationError('quantity', saveError)}} </span>
                        </div>

                        <div class="w-full px-4 py-3">
                            <button class="text-center text-white" 
                                style="height: 40px; width: 100%; border: 1px solid black; border-radius: 5px; background: #003865"
                                @click="dispenseMedicine()"
                            >
                                Submit
                            </button>
                        </div>

                        
                    </div>
                </div>

            </div>

            
        </div>
    </Navigation>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import Navigation from "../Layouts/Sidebar";
import Table from "../Components/Table";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table
    },
    data(){
        return {
            patient: null,
            columns: [
                'Medicine', 'Quantity'
            ],
            keys : [
                {
                    label: 'medicine_name',
                },
                {
                    label: 'quantity',
                },
            ],
            selected: null,
            form: {
                patient_id: null,
                medicine_id: null,
                quantity: 1
            },

            saveError: null
        }
    },
    created(){
        this.patient = this.options.patient
        this.form.patient_id = this.patient.id
        this.form.medicine_id = this.options.availableMedicines[0].id

        console.log(this.options)
        
    },
    methods: {
        back(){
            Inertia.get(
                this.$root.route + '/patients',
                {
                    onSuccess: () => { },
                },
            );
        },

        dispenseMedicine() {
            Inertia.post(this.$root.route + '/patients/dispense-medicine', this.form,
            {
                onSuccess: (res) => {
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