<template>
    <div class="w-screen h-screen --main">
        <Navigation :auth="auth">
            <div class="px-4 pt-12">
                <div class="w-full">
                    <span class="text-2xl">
                        <i class="fa-solid fa-arrow-left mr-2 cursor-pointer" @click="back()"></i> Consultation Forms
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
                            <option value="Tuberculosis Symptom Form" v-if="auth.user_type == 'doctor' || auth.user_type == 'leader' || auth.user_type == 'member'">
                                TB-Dots
                            </option>

                            <option value="Pregnancy Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Pregnancy Form
                            </option>

                            <option value="Prenatal Registration Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Prenatal Registration Form
                            </option>

                            <option value="Postnatal Registration Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Postnatal Registration Form
                            </option>

                            <option value="Nutrition Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Nutrition Form
                            </option>
                            
                            <option value="Vaccination Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Vaccination Form
                            </option>
                            <option value="Deworming Form" v-if="auth.user_type == 'nurse' || auth.user_type == 'midwife'">
                                Deworming Form
                            </option>
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
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
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

                    <!-- Prenatal Registration Form -->
                    <div class="w-full" v-if="activeForm == 'Prenatal Registration Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full">
                            <span class="float-right cursor-pointer p-4" 
                                @click="activeForm = null"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>
                        
                        <div class="w-full flex-col p-5">
                            

                            <div class="w-full">
                                <label>Today's Date:</label><br>
                                <input type="date" class="--input" v-model="prenatal.date_today"><br>
                                <span class="text-xs text-red-500 pl-2">{{validationError('date_today', saveError)}} </span>
                            </div>

                            <div class="w-full mt-10 font-bold">
                                PREGNANCY CARE INFORMATION
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Provider Name:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_name">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_name', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>NPI #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.npi">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('npi', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>PhilHealth ID # or Billing ID #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.billing_id">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('billing_id', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Tax ID #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.tax_id">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('tax_id', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Provider Address:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_address">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_address', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>City:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.city">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('city', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Town:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.town">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('town', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>ZIP:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.zip">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('zip', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Provider Phone:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_phone">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_phone', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Provider Fax:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.provider_fax">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('provider_fax', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full mt-10 font-bold">
                                MEMBER INFORMATION
                            </div>

                            <div class="w-full mt-4">
                                <b>NOTE:</b> Verify the member’s eligibility on the secure Provider portal before rendering services.
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Member Name:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_name">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_name', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Member ID #:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_id">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_id', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>DOB:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.dob">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Member Address:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_address">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_address', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Phone:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_phone">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_phone', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>City:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_city">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_city', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Town:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_town">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_town', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>ZIP:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.member_zip">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('member_zip', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>LMP:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.lmp">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('lmp', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>G:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.g">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('g', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>P:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.p">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('p', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>EDD:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.edd">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('edd', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full pr-2">
                                    <label>Date of first prenatal visit:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.first_prenatal">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('first_prenatal', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Date of most recent prenatal visit:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.recent_prenatal">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('recent_prenatal', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Type of last delivery:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.type_of_last_delivery">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('type_of_last_delivery', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Date of last delivery:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.last_delivery">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('last_delivery', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Expected delivery facility:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.delivery_facility">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('delivery_facility', saveError)}} </span>
                                </div>
                            </div>

                            <div class="w-full mt-10 font-bold">
                                RISK ASSESSMENT
                            </div>

                            <div class="w-full mt-4">
                                <b>NOTE:</b> Check all applicable risks. You may attach your own risk assessment form if necessary.
                            </div>

                            <div class="w-full flex flex-row mt-4">
                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Behavioral risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.behavioral_risks"  value="Smokes more than 10 cigarettes per day">
                                        <label> Smokes more than 10 cigarettes per day</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Less than 2 years since last pregnancy">
                                        <label> Less than 2 years since last pregnancy</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Directed member to WIC office">
                                        <label> Directed member to WIC office</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Offered HIV counseling">
                                        <label> Offered HIV counseling</label><br>

                                        <input type="checkbox" v-model="prenatal.behavioral_risks" value="Substance use in pregnancy">
                                        <label> Substance use in pregnancy</label><br>
                                    </div>
                                </div>

                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Psychological risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Adolescent">
                                        <label>Adolescent  </label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate finances">
                                        <label>Inadequate finances </label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate housing">
                                        <label>Inadequate housing</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Inadequate social supports">
                                        <label>Inadequate social supports</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Involvement with other agencies, e.g., DCF">
                                        <label>Involvement with other agencies, e.g., DCF</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Less than high school education">
                                        <label>Less than high school education</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Poor nutrition">
                                        <label>Poor nutrition</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Psychiatric history">
                                        <label>Psychiatric history</label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Significant learning disabilities">
                                        <label>Significant learning disabilities </label><br>

                                        <input type="checkbox" v-model="prenatal.pyschological_risks" value="Violence/abuse">
                                        <label>Violence/abuse</label><br>

                                    </div>
                                </div>

                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Medical risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Cardiac disease">
                                        <label>Cardiac disease </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Diabetes">
                                        <label>Diabetes </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Endocrine disorders">
                                        <label>Endocrine disorders </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="GI disorders">
                                        <label>GI disorders </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Hypothyroid">
                                        <label>Hypothyroid </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Malignancy">
                                        <label>Malignancy </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Moderate or severe asthma">
                                        <label>Moderate or severe asthma</label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Renal disease/history of UTIs">
                                        <label>Renal disease/history of UTIs </label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Seizure disorders">
                                        <label> Seizure disorders</label><br>

                                        <input type="checkbox" v-model="prenatal.medical_risks" value="Sickle cell disease">
                                        <label> Sickle cell disease</label><br>

                                    </div>
                                </div>

                                <div class="w-full flex-col pr-2">
                                    <div class="w-full font-bold">
                                        Obstetrics risks
                                    </div>

                                    <div class="w-full mt-5">
                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Abnormal pap smear">
                                        <label>Abnormal pap smear </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Anemia">
                                        <label>Anemia </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="BMI > 30">
                                        <label>BMI > 30 </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Gestational diabetes">
                                        <label>Gestational diabetes </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of cervical uterine infections">
                                        <label>History of cervical uterine infections </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of infant with brain injury, neurological defect or congenial abnormality">
                                        <label>
                                            History of infant with brain injury, neurological defect or congenial abnormality
                                        </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of infertility">
                                        <label>History of infertility </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Inadequate prenatal care">
                                        <label>Inadequate prenatal care </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="History of low birth weight infant">
                                        <label>History of low birth weight infant </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Incompetent cervix">
                                        <label>Incompetent cervix </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Intrauterine growth retardation (IUGR)">
                                        <label>Intrauterine growth retardation (IUGR) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Placenta Previa/Placenta Abruptia">
                                        <label>Placenta Previa/Placenta Abruptia </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Polyhydramnios/oligohydramnios">
                                        <label>Polyhydramnios/oligohydramnios </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Poor weight gain">
                                        <label>Poor weight gain </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Postpartum depression – family history">
                                        <label>Postpartum depression – family history </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Postpartum depression – personal history">
                                        <label>Postpartum depression – personal history </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Pregnancy-induced hypertension (PIH)">
                                        <label>Pregnancy-induced hypertension (PIH) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Pre-pregnancy weight of less than 100 lbs.">
                                        <label>Pre-pregnancy weight of less than 100 lbs. </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous pre-term labor (PTL)">
                                        <label>Previous pre-term labor (PTL) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous pre-term delivery (PTD)">
                                        <label>Previous pre-term delivery (PTD) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Previous stillborn/neonatal death">
                                        <label>Previous stillborn/neonatal death </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Multiple gestation">
                                        <label>Multiple gestation </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Rh sensitization">
                                        <label>Rh sensitization </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Sexually transmitted disease (STD)">
                                        <label>Sexually transmitted disease (STD) </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="2 or more spontaneous abortions (SABs) or therapeutic abortions (TABs)">
                                        <label>
                                            2 or more spontaneous abortions (SABs) or therapeutic abortions (TABs)
                                        </label><br>

                                        <input type="checkbox" v-model="prenatal.obstetrics_risks" value="Uterine/cervical anomaly">
                                        <label>
                                            Uterine/cervical anomaly
                                        </label><br>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full flex flex-row mt-10">
                                <div class="w-full pr-2">
                                    <label>OB/GYN provider signature:</label><br>
                                    <input type="text" class="--input w-full" v-model="prenatal.ob_gyn">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('ob_gyn', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <label>Date:</label><br>
                                    <input type="date" class="--input w-full" v-model="prenatal.date">
                                    <span class="text-xs text-red-500 pl-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full pr-2">
                                    <br>
                                    <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                        @click="createPrenatal()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Nutrition FOrm -->
                    <div class="w-full flex-col" v-if="activeForm == 'Nutrition Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Gurdian Name:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.guardian_name">
                                <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Birth:</label><br>
                                <input type="date" class="--input w-full" v-model="nutrition.dob">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.age">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Height:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.height">
                                <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                            </div>

                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.weight">
                                <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>BMI:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.bmi">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Vitamis:</label><br>
                                <input type="text" class="--input w-full" v-model="nutrition.vitamins">
                                <span class="text-xs text-red-500 pl-2">{{validationError('vitamins', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createNutritionForm()"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Deworming FOrm -->
                    <div class="w-full flex-col" v-if="activeForm == 'Deworming Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Gurdian Name:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.guardian_name">
                                <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Birth:</label><br>
                                <input type="date" class="--input w-full" v-model="deworming.dob">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.age">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Height:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.height">
                                <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                            </div>

                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.weight">
                                <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>BMI:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.bmi">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Deworming Medication:</label><br>
                                <input type="text" class="--input w-full" v-model="deworming.deworming_medication">
                                <span class="text-xs text-red-500 pl-2">{{validationError('deworming_medication', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createDewormingForm()"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Vaccination FOrm -->
                    <div class="w-full flex-col" v-if="activeForm == 'Vaccination Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Gurdian Name:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.guardian_name">
                                <span class="text-xs text-red-500 pl-2">{{validationError('guardian_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Birth:</label><br>
                                <input type="date" class="--input w-full" v-model="vaccination.dob">
                                <span class="text-xs text-red-500 pl-2">{{validationError('dob', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.age">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Height:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.height">
                                <span class="text-xs text-red-500 pl-2">{{validationError('height', saveError)}} </span>
                            </div>

                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.weight">
                                <span class="text-xs text-red-500 pl-2">{{validationError('weight', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>BMI:</label><br>
                                <input type="text" class="--input w-full" v-model="vaccination.bmi">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bmi', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Vaccine:</label><br>
                                <select v-model="vaccination.vaccination_id" class="--input w-full">
                                    <option v-for="vaccine in options.vaccinations" :value="vaccine.id" :key="vaccine.id">{{vaccine.name}}</option>
                                </select>
                                <span class="text-xs text-red-500 pl-2">{{validationError('vaccination_id', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createVaccinationForm()"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Postnatal Registration Form -->
                    <div class="w-full flex-col" v-if="activeForm == 'Postnatal Registration Form'" style="border: 1px solid black; border-radius: 5px">
                        <div class="w-full">
                            <span class="float-right cursor-pointer p-2" 
                                @click="activeForm = null; formData.tb = []; formData.lmp = null; formData.edc = null; formData.edd = null; selectedForm = null;"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Hospital Name:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.hospital_name">
                                <span class="text-xs text-red-500 pl-2">{{validationError('hospital_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Client's Name:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.clients_name">
                                <span class="text-xs text-red-500 pl-2">{{validationError('clients_name', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Address:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.address">
                                <span class="text-xs text-red-500 pl-2">{{validationError('address', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Occupation:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.occupation">
                                <span class="text-xs text-red-500 pl-2">{{validationError('occupation', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Type of Delivery:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.type_of_delivery">
                                <span class="text-xs text-red-500 pl-2">{{validationError('type_of_delivery', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Mode of Delivery:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.mode_of_delivery">
                                <span class="text-xs text-red-500 pl-2">{{validationError('mode_of_delivery', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Total Hours of Labor:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.total_hours_of_labor">
                                <span class="text-xs text-red-500 pl-2">{{validationError('total_hours_of_labor', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Postnatal Day:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.postnatal_day">
                                <span class="text-xs text-red-500 pl-2">{{validationError('postnatal_day', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Registration #:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.register_no">
                                <span class="text-xs text-red-500 pl-2">{{validationError('register_no', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Date of Arrival:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.doa">
                                <span class="text-xs text-red-500 pl-2">{{validationError('doa', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Religion:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.religion">
                                <span class="text-xs text-red-500 pl-2">{{validationError('religion', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Gestational Age:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.gestational_age">
                                <span class="text-xs text-red-500 pl-2">{{validationError('gestational_age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Last Menstrual Period:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.lmp">
                                <span class="text-xs text-red-500 pl-2">{{validationError('lmp', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>New Born Sex:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.new_born_sex">
                                <span class="text-xs text-red-500 pl-2">{{validationError('new_born_sex', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Days of Hospital Stay:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.days_of_hospital_stay">
                                <span class="text-xs text-red-500 pl-2">{{validationError('days_of_hospital_stay', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Age:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.age">
                                <span class="text-xs text-red-500 pl-2">{{validationError('age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Education:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.education">
                                <span class="text-xs text-red-500 pl-2">{{validationError('education', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Obstetrical Score:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.obstetrical_score">
                                <span class="text-xs text-red-500 pl-2">{{validationError('obstetrical_score', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Chief Complain:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.chief_complain">
                                <span class="text-xs text-red-500 pl-2">{{validationError('chief_complain', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Estimated Date of Delivery:</label><br>
                                <input type="date" class="--input w-full" v-model="postnatal.edd">
                                <span class="text-xs text-red-500 pl-2">{{validationError('edd', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>New Born Weight:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.newborn_weight">
                                <span class="text-xs text-red-500 pl-2">{{validationError('newborn_weight', saveError)}} </span>
                            </div>


                            <div class="w-full pr-2">
                                <label>Elimination Pattern:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.elimination_pattern">
                                <span class="text-xs text-red-500 pl-2">{{validationError('elimination_pattern', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Activity and Exercise:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.activity_and_exercise">
                                <span class="text-xs text-red-500 pl-2">{{validationError('activity_and_exercise', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Menarche Age:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.menarche_age">
                                <span class="text-xs text-red-500 pl-2">{{validationError('menarche_age', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Period:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.period">
                                <span class="text-xs text-red-500 pl-2">{{validationError('period', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Bad Habits:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.bad_habits">
                                <span class="text-xs text-red-500 pl-2">{{validationError('bad_habits', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Nutritional Pattern:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.nutritional_pattern">
                                <span class="text-xs text-red-500 pl-2">{{validationError('nutritional_pattern', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Sleeping Pattern</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.sleeping_pattern">
                                <span class="text-xs text-red-500 pl-2">{{validationError('sleeping_pattern', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Cycle:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.cycle">
                                <span class="text-xs text-red-500 pl-2">{{validationError('cycle', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Amount of Blood Loss:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.amount_of_blood_loss">
                                <span class="text-xs text-red-500 pl-2">{{validationError('amount_of_blood_loss', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Duration of Marriage:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.duration_of_marriage">
                                <span class="text-xs text-red-500 pl-2">{{validationError('duration_of_marriage', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Family Planning Method Adopted:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.family_planning_method_adopted">
                                <span class="text-xs text-red-500 pl-2">{{validationError('family_planning_method_adopted', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Diseases</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.diseases">
                                <span class="text-xs text-red-500 pl-2">{{validationError('diseases', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Genitics Condition:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.genitics_condition">
                                <span class="text-xs text-red-500 pl-2">{{validationError('genitics_condition', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Parity:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.parity">
                                <span class="text-xs text-red-500 pl-2">{{validationError('parity', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Gravidity:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.gravidity">
                                <span class="text-xs text-red-500 pl-2">{{validationError('gravidity', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Miscarriages:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.miscarriages">
                                <span class="text-xs text-red-500 pl-2">{{validationError('miscarriages', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Terminations</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.terminations">
                                <span class="text-xs text-red-500 pl-2">{{validationError('terminations', saveError)}} </span>
                            </div>
                        </div>

                        <div class="w-full flex flex-row p-4">
                            <div class="w-full pr-2">
                                <label>Previous Pregnancy:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.previous_pregnancy">
                                <span class="text-xs text-red-500 pl-2">{{validationError('previous_pregnancy', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Length of Pregnancy:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.length_of_pregnancy">
                                <span class="text-xs text-red-500 pl-2">{{validationError('length_of_pregnancy', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2">
                                <label>Induction:</label><br>
                                <input type="text" class="--input w-full" v-model="postnatal.induction">
                                <span class="text-xs text-red-500 pl-2">{{validationError('induction', saveError)}} </span>
                            </div>

                            <div class="w-full pr-2 mt-5">
                                <button class="w-full mx-2" style="background: black; color: white; border: 1px solid white; border-radius: 5px; height: 43px;"
                                    @click="createPostnatalForm()"
                                >
                                    Save
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
import Table from "../Components/Table";
import * as htmlToImage from 'html-to-image';
import { toPng, toJpeg, toBlob, toPixelData, toSvg } from 'html-to-image';
import axios from "axios";

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
                'Name', 'Description'
            ],
            keys : [
                {
                    label: 'name',
                },
                {
                    label: 'description',
                }
            ],
            selectedForm: null,
            formData: {
                id: null,
                patient_id: null,
                description: null,
                name: null,
                tb: [],
                lmp: null,
                edc: null,
                edd: null
            },
            prenatal: {
                id: null,
                patient_id: null,
                date_today: null,
                provider_name: null,
                npi: null,
                billing_id: null,
                tax_id: null,
                provider_address: null,
                city: null,
                town: null,
                zip: null,
                provider_phone: null,
                provider_fax: null,
                member_name: null,
                member_id: null,
                dob: null,
                member_address: null,
                member_phone: null,
                member_city: null,
                member_town: null,
                member_zip: null,
                lmp: null,
                g: null,
                p: null,
                edd: null,
                first_prenatal: null,
                recent_prenatal: null,
                type_of_last_delivery: null,
                last_delivery: null,
                delivery_facility: null,
                behavioral_risks: [],
                pyschological_risks: [],
                medical_risks: [],
                obstetrics_risks: [],
                ob_gyn: null,
                date: null,
            },
            postnatal: {
                id: null,
                patient_id: null,
                hospital_name: null,
                clients_name: null,
                address: null,
                occupation: null,
                type_of_delivery: null,
                mode_of_delivery: null,
                total_hours_of_labor: null,
                postnatal_day: null,
                register_no: null,
                doa: null,
                religion: null,
                gestational_age: null,
                lmp: null,
                new_born_sex: null,
                days_of_hospital_stay: null,
                age: null,
                education: null,
                obstetrical_score: null,
                chief_complain: null,
                edd: null,
                newborn_weight: null,
                bad_habits: null,
                elimination_pattern: null,
                activity_and_exercise: null,
                menarche_age: null,
                period: null,
                nutritional_pattern: null,
                sleeping_pattern: null,
                cycle: null,
                amount_of_blood_loss: null,
                duration_of_marriage: null,
                family_planning_method_adopted: null,
                diseases: null,
                genitics_condition: null,
                gravidity: null,
                parity: null,
                miscarriages: null,
                terminations: null,
                previous_pregnancy: null,
                length_of_pregnancy: null,
                induction: null,
            },
            nutrition: {
                id: null,
                patient_id: null,
                guardian_name: null,
                dob: null,
                age: null,
                height: null,
                weight: null,
                bmi: null,
                vitamins: null,
            },
            deworming: {
                id: null,
                patient_id: null,
                guardian_name: null,
                dob: null,
                age: null,
                height: null,
                weight: null,
                bmi: null,
                deworming_medication: null,
            },
            vaccination: {
                id: null,
                patient_id: null,
                guardian_name: null,
                dob: null,
                age: null,
                height: null,
                weight: null,
                bmi: null,
                vaccination_id: null,
            },
            checked: [],
            saveError: null
        }
    },

    created() {
        this.patient = this.options.patient

        this.formData.patient_id = this.patient.id

        this.formData.name = this.formName

        this.prenatal.patient_id = this.patient.id
        this.postnatal.patient_id = this.patient.id
        this.nutrition.patient_id = this.patient.id
        this.deworming.patient_id = this.patient.id
        this.vaccination.patient_id = this.patient.id
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
            if(arg.name == 'Prenatal Registration Form') {
                this.prenatal = Object.assign({}, arg);
            }else if (arg.name == 'Postnatal Registration Form') {
                this.postnatal = Object.assign({}, arg);
            } else if(arg.name == 'Nutrition Form') {
                this.nutrition = Object.assign({}, arg);
            } else if(arg.name == 'Deworming Form') {
                this.deworming = Object.assign({}, arg);
                } else if(arg.name == 'Vaccination Form') {
                this.vaccination = Object.assign({}, arg);
            } else {
                this.formData.id = arg.id
                this.formData.name = arg.name
                this.formData.description = arg.description
                this.formData.patient_id = arg.patient_id
                this.formData.tb = arg.tb
                this.formData.lmp = arg.lmp
                this.formData.edc = arg.edc
                this.formData.edd = arg.edd
            }
            

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
            this.formData.description = this.activeForm

            Inertia.post(this.$root.route + '/patients/create-patient/form', this.formData,
            {
                onSuccess: (res) => {
                    location.reload()
                },
                onError: (err) => {
                }
            });
        },

        createPrenatal(){
            Inertia.post(this.$root.route + '/patients/create-patient/prenatal', this.prenatal,
            {
                onSuccess: (res) => {
                    location.reload()
                },
                onError: (err) => {
                    this.saveError = err
                }
            });
        },
        createNutritionForm(){
            Inertia.post(this.$root.route + '/patients/create-patient/nutrition', this.nutrition,
            {
                onSuccess: (res) => {
                    location.reload()
                },
                onError: (err) => {
                    console.log(err)
                    this.saveError = err
                }
            });
        },
        createDewormingForm(){
            Inertia.post(this.$root.route + '/patients/create-patient/deworming', this.deworming,
            {
                onSuccess: (res) => {
                    location.reload()
                },
                onError: (err) => {
                    this.saveError = err
                }
            });
        },

        createVaccinationForm() {
            Inertia.post(this.$root.route + '/patients/create-patient/vaccination', this.vaccination,
            {
                onSuccess: (res) => {
                    location.reload()
                },
                onError: (err) => {
                    this.saveError = err
                }
            });
        },

        createPostnatalForm(){
            Inertia.post(this.$root.route + '/patients/create-patient/postnatal', this.postnatal,
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

<style scoped>
    .--input {
        height: 40px;
        border: 1px solid black;
        border-radius: 5px;
        padding: 5px 10px 5px 10px;
    }

    label {
        font-weight: bold;
    }

    .--main {

    }
</style>