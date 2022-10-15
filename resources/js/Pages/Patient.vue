<template>
    <Navigation :auth="auth">
        <div class="px-4 pt-12">
            <div class="w-full">
                <span class="text-2xl">
                    <i class="fa-solid fa-arrow-left mr-2 cursor-pointer" @click="back()"></i> Patient Forms
                </span>

                <span class="text-2xl float-right font-bold">
                    {{ patient.name }} ({{ patient.place.name }})
                </span>
            </div>

            <div class="w-full mt-10">
                <div class="w-full inline-block">
                    <select v-model="formName"
                        style="width: 230px; height: 30px; border: 1px solid black"
                    >
                        <option value="Tuberculosis Symptom Form">Tuberculosis Symptom Form</option>
                        <option value="Pregnancy Form">Pregnancy Form</option>
                    </select>

                    <button style="height: 30px; border: 1px solid black; background: #003865; padding: 0px 20px 0px 20px" class="text-white"
                        @click="activeForm = formName; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null;"
                    >
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="w-full mt-5">
                <Table :columns="columns" :rows="options.forms" :keys="keys" :selected.sync="selectedForm"
                    v-if="!activeForm && !selectedForm"
                />

                <!-- TB Form -->
                <div class="w-full" v-if="activeForm == 'Tuberculosis Symptom Form'">
                    <div class="w-full">
                        <span class="float-right cursor-pointer p-4" 
                            @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full p-4"
                        style="height: auto; border: 1px solid black; border-radius: 5px"
                    >

                        <p class="mb-2 font-bold">
                            Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung mayroon ng alinman sa mga sumusunod na sintomas at angkop na tagal ng sintomas na nararanasan:
                        </p>

                        <div class="w-full flex flex-row text-sm">
                            <div class="flex flex-col"
                                style="width: 70%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="1" v-model="formData.tb">
                                    <label> Ubo</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="2" v-model="formData.tb">
                                    <label> Pag-ubo na may kasamang dugo</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="3" v-model="formData.tb">
                                    <label> Lagnat na di maipaliwanag ang sanhi</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="4" v-model="formData.tb">
                                    <label> Pamamayat na di maipaliwanag ang sanhi</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="5" v-model="formData.tb">
                                    <label> Labis na pagpapawis sa hapon o gabi na walang kinalaman sa init ng panahon</label>
                                </div>
                            </div>

                            <div class="flex flex-col"
                                style="width: 10%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="6" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="7" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="8" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="9" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="10" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                            </div>

                            <div class="flex flex-col"
                                style="width: 10%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="11" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="12" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="13" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="14" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="15" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>
                            </div>

                            <div class="flex flex-col"
                                style="width: 10%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="16" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="17" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="18" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="19" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="20" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                
                            </div>
                        </div>

                        <!--  -->

                        <p class="mb-2 mt-4 font-bold">
                            Karagdagang tanong para sa mga batang edad 0-14:
                        </p>

                        <div class="w-full flex flex-row text-sm">
                            <div class="flex flex-col"
                                style="width: 70%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="21" v-model="formData.tb">
                                    <label> Ubo o kahirapan sa paghinga</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="22" v-model="formData.tb">
                                    <label> Mabilis mapagod</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="23" v-model="formData.tb">
                                    <label> Pananamlay</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="24" v-model="formData.tb">
                                    <label> Walang gana o mahinang kumain</label>
                                </div>

                            </div>

                            <div class="flex flex-col"
                                style="width: 10%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="25" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="26" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="27" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="28" v-model="formData.tb">
                                    <label> &gt;2 weeks</label>
                                </div>

                            </div>

                            <div class="flex flex-col"
                                style="width: 10%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="29" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="30" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="31" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="32" v-model="formData.tb">
                                    <label> &lt;2 weeks</label>
                                </div>
                            </div>

                            <div class="flex flex-col"
                                style="width: 10%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="33" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="32" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="35" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="36" v-model="formData.tb">
                                    <label> Wala</label>
                                </div>

                            </div>
                        </div>

                        <!--  -->

                        <p class="mb-2 mt-4 font-bold">
                            Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung mayroon ng alinman sa mga sumusunod na risk factors:
                        </p>

                        <div class="w-full flex flex-row text-sm">
                            <div class="flex flex-col"
                                style="width: 70%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="37" v-model="formData.tb">
                                    <label> Dating nag-gamutan sa sakit na TB</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="38" v-model="formData.tb">
                                    <label> May nakakasamang may sakit na TB sa matagal na panahon</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="39" v-model="formData.tb">
                                    <label> Diabetes | Cancer | Sumasailalim sa dialysis</label>
                                </div>
                            </div>



                            <div class="flex flex-col"
                                style="width: 30%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="40" v-model="formData.tb">
                                    <label> Edad 60 anyos pataas</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="41" v-model="formData.tb">
                                    <label> Indigent | 4P's/CCT Member</label>
                                </div>

                                <div class="w-full">
                                    <input type="checkbox" value="42" v-model="formData.tb">
                                    <label> Naninigarilyo | Madalas uminom ng alak</label>
                                </div>
                            </div>
                        </div>

                        <p class="mb-2 mt-4 font-bold">
                            Lagyan ng <span> <i class="fa-solid fa-check"></i> </span> kung anong aksyon ang ginawa para sa kliyente:
                        </p>

                        <div class="w-full flex flex-row text-sm mb-8">
                            <div class="flex flex-col"
                                style="width: 70%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="43" v-model="formData.tb">
                                    <label> Ni-refer sa health center dahil mayroon ng alinman sintomas o risk factor</label>
                                </div>
                            </div>



                            <div class="flex flex-col"
                                style="width: 30%"
                            >
                                <div class="w-full">
                                    <input type="checkbox" value="44" v-model="formData.tb">
                                    <label> Hindi ini-refer sa health center</label>
                                </div>
                            </div>
                        </div>

                        <div class="w-full mb-2">
                            <button class="text-white" style="padding: 5px 20px 5px 20px; background: #003865; border-radius: 5px"
                                @click="generateForm()"
                                :class="{ 'cursor-not-allowed': formData.tb.length == 0, 'cursor-pointer': formData.tb.length > 0 }"
                                :disabled="formData.tb.length == 0"
                            >
                                Submit
                            </button>
                        </div>

                        <!--  -->
                        
                    </div>
                </div>
                <!-- End of TB Form -->

                <!-- Pregnancy Form -->
                <div class="w-full" v-if="activeForm == 'Pregnancy Form'" style="border: 1px solid black; border-radius: 5px">
                    <div class="w-full">
                        <span class="float-right cursor-pointer p-2" 
                            @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full mt-2 mb-5 px-4 flex flex-row"
                        style="height: auto;"
                    >   
                        <div class="w-full">
                            <label class="text-xl font-bold"> Last Menstrual Period</label> <br>
                            <input type="date" v-model="formData.lmp" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px">
                        </div>

                        <div class="w-full">
                            <label class="text-xl font-bold"> Expected Date of Confinement</label> <br>
                            <input type="date" v-model="formData.edc" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px">
                        </div>

                        <div class="w-full">
                            <label class="text-xl font-bold"> Expected Date of Delivery</label> <br>
                            <input type="date" v-model="formData.edd" class="mt-2" style="height: 40px; border: 1px solid black; border-radius: 5px; padding: 5px 10px 5px 10px">
                        </div>
                    </div>

                    <div class="w-full mb-5 px-4">
                        <button class="text-white" style="padding: 5px 20px 5px 20px; background: #003865; border-radius: 5px"
                            @click="generateForm()"
                            :class="{ 'cursor-not-allowed': !formData.lmp || !formData.edc || !formData.edd, 'cursor-pointer': !!formData.lmp && !!formData.edc && !!formData.edd }"
                            :disabled="!formData.lmp || !formData.edc || !formData.edd"
                        >
                            Submit
                        </button>
                    </div>
                        
                </div>

                <!-- End of Pregnancy Form -->

            </div>
        </div>
    </Navigation>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import Navigation from "../Layouts/Sidebar";
import Table from "../Components/Table";
import * as htmlToImage from 'html-to-image';
import { toPng, toJpeg, toBlob, toPixelData, toSvg } from 'html-to-image';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table
    },
    data(){
        return {
            patient: null,
            activeForm: null,
            formName: 'Tuberculosis Symptom Form',
            columns: [
                'Name', 'Description', 'Reference #'
            ],
            keys : [
                {
                    label: 'name',
                },
                {
                    label: 'description',
                },
                {
                    label: 'reference',
                },
            ],
            selectedForm: null,
            formData: {
                id: null,
                patient_id: null,
                description: 'Sample description!!',
                name: null,
                tb: [],
                lmp: null,
                edc: null,
                edd: null
            },
            checked: []
        }
    },

    created() {
        this.patient = this.options.patient

        this.formData.patient_id = this.patient.id

        this.formData.name = this.formName
    },

    watch: {
        activeForm(arg) {
            this.formData.name = arg
        },

        'formData.tb': {
            handler: function (val, oldVal) {
            },
            deep: true
        },

        selectedForm(arg) {
            this.formData.id = arg.id
            this.formData.name = arg.name
            this.formData.description = arg.description
            this.formData.patient_id = arg.patient_id
            this.formData.tb = arg.tb
            this.formData.lmp = arg.lmp
            this.formData.edc = arg.edc
            this.formData.edd = arg.edd

            this.formName = arg.name
            this.activeForm = arg.name

        }
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

        generateForm(){
            Inertia.post(this.$root.route + '/patients/create-patient/form', this.formData,
            {
                onSuccess: (res) => {
                    location.reload()
                },
                onError: (err) => {
                }
            });
        }
    }
}

</script>