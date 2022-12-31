<template>
    <div class="w-full h-screen">
        <Navigation :auth="auth">
            <div class="w-full flex flex-col">
                <div class="w-full flex flex-row mt-8" style="height: 5vh;">
                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        @click="activeTab = 'personal'"
                        :class="{'--bg_gray': activeTab == 'personal'}"
                    >
                        Personal Medical History
                    </div>

                    <div class="w-full flex justify-center items-center text-4xl cursor-pointer"
                        :class="{'--bg_gray': activeTab == 'family'}"
                        @click="activeTab = 'family'"
                    >
                        Family Medical History
                    </div>
                </div>

                <!--  -->
                <div class="w-full flex flex-col p-4" v-if="activeTab == 'personal'">
                    <div class="w-full flex flex-col">
                        <p class="text-2xl ml-2">
                            ALLERGIES
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeAllergy" @click="activeAllergy = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeAllergy" @click="activeAllergy = false; formAllergy.id = null; formAllergy.allergy = null; formAllergy.allergic_reaction = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('allergy')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col"  v-if="activeAllergy">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Allergy:</label><br>
                                    <input type="text" v-model="formAllergy.allergy" placeholder="Allergy" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('allergy', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Allergic Reaction:</label><br>
                                    <input type="text" v-model="formAllergy.allergic_reaction" placeholder="Allergic Reaction" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('allergic_reaction', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px" 
                                        @click="saveAllergy()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="allergy"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['ALLERGY', 'ALLERGIC REACTION']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.allergies" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'allergy'}, {label: 'allergic_reaction'}]" :key="i" class="cursor-pointer --td"> 
                                                    <span>{{options.allergies[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeAllergy ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['ALLERGY', 'ALLERGIC REACTION']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.allergies" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'allergy'}, {label: 'allergic_reaction'}]" :key="i" class="cursor-pointer"
                                            @click="formAllergy = Object.assign({}, l); activeAllergy = true"
                                        >
                                            <span>{{options.allergies[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10 ml-2">
                                MEDICATIONS
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeMedication" @click="activeMedication = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeMedication" @click="activeMedication = false; formMedication.id = null; formMedication.medicaions = null; formMedication.dose = null; formMedication.times_per_day = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('medication')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row" >
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeMedication">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Medications:</label><br>
                                    <input type="text" v-model="formMedication.medications" placeholder="Medications" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('medicaions', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Dose:</label><br>
                                    <input type="text" v-model="formMedication.dose" placeholder="Dose" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dose', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Times Per Day:</label><br>
                                    <input type="text" v-model="formMedication.times_per_day" placeholder="Times Per Day" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('times_per_day', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveMedication()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="medication"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['MEDICATIONS', 'DOSE', 'TIMES PER DAY']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.medications" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'medications'}, {label: 'dose'}, {label: 'times_per_day'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.medications[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeMedication ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['MEDICATIONS', 'DOSE', 'TIMES PER DAY']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.medications" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'medications'}, {label: 'dose'}, {label: 'times_per_day'}]" :key="i" class="cursor-pointer"
                                            @click="formMedication = Object.assign({}, l); activeMedication = true"
                                        >
                                            <span>{{options.medications[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>


                        <p class="text-2xl mt-10 ml-2">
                                HEALTH MAINTENANCE HISTORY
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeMaintenance" @click="activeMaintenance = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeMaintenance" @click="activeMaintenance = false; formMaintenance.id = null; formMaintenance.type = null; formMaintenance.facility = null; formMaintenance.abnormal_result = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('maintenance')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row" >
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeMaintenance">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <select class="--input" v-model="formMaintenance.type">
                                        <option :value="'CHOLESTEROL'">
                                            CHOLESTEROL
                                        </option>

                                        <option :value="'COLONOSCOPY/SIGMOID'">
                                            COLONOSCOPY/SIGMOID
                                        </option>
                                        
                                        <option :value="'MAMMOGRAM'">
                                            MAMMOGRAM
                                        </option>

                                        <option :value="'PAP SMEAR'">
                                            PAP SMEAR
                                        </option>

                                        <option :value="'BONE DENSITY'">
                                            BONE DENSITY
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date:</label><br>
                                    <input type="date" v-model="formMaintenance.date" placeholder="Date" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Facility/Provider:</label><br>
                                    <input type="text" v-model="formMaintenance.facility" placeholder="Facility/Provider" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('facility', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Abnormal Result:</label><br>
                                    <select class="--input" v-model="formMaintenance.abnormal_result">
                                        <option :value="'YES'">
                                            YES
                                        </option>

                                        <option :value="'NO'">
                                            NO
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('abnormal_result', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveMaintenance()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="maintenance"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['TYPE', 'DATE', 'FACILITY/PROVIDER', 'ABNORMAL RESULT']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.maintenance" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}, {label: 'facility'}, {label: 'abnormal_result'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.maintenance[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeMaintenance ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['TYPE', 'DATE', 'FACILITY/PROVIDER', 'ABNORMAL RESULT']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.maintenance" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}, {label: 'facility'}, {label: 'abnormal_result'}]" :key="i" class="cursor-pointer"
                                            @click="formMaintenance = Object.assign({}, l); activeMaintenance = true"
                                        >
                                            <span>{{options.maintenance[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10 ml-2">
                            VACCINATION HISTORY
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeVaccination" @click="activeVaccination = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeVaccination" @click="activeVaccination = false; formVaccination.id = null; formVaccination.type = null; formVaccination.date = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('vaccination')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeVaccination">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <select class="--input" v-model="formVaccination.type">
                                        <option :value="'Last Tetanus Booster or TdaP'">
                                            Last Tetanus Booster or TdaP
                                        </option>

                                        <option :value="'Last Flu Vaccine:'">
                                            Last Flu Vaccine
                                        </option>
                                        
                                        <option :value="'Last Zoster Vaccine (Shingles)'">
                                            Last Zoster Vaccine (Shingles)
                                        </option>

                                        <option :value="'Last Pnuemovax (Pneumonia)'">
                                            Last Pnuemovax (Pneumonia)
                                        </option>

                                        <option :value="'Last Prevnar'">
                                            Last Prevnar
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date:</label><br>
                                    <input type="date" v-model="formVaccination.date" placeholder="Date" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveVaccination()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="vaccination"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['TYPE', 'DATE']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.vaccinations" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.vaccinations[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeVaccination ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['TYPE', 'DATE']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.vaccinations" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}]" :key="i" class="cursor-pointer"
                                            @click="formVaccination = Object.assign({}, l); activeVaccination = true"
                                        >
                                            <span>{{options.vaccinations[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10 ml-2">
                            DISEASE HISTORY
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeDisease" @click="activeDisease = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeDisease" @click="activeDisease = false ; formDisease.id = null; formDisease.disease = null; formDisease.other = null; formDisease.type = null; formDisease.status = null; formDisease.comment = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('disease')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeDisease">
                                <div class="w-full p-2" v-if="!formDisease.other">
                                    <label class="text-bold text-xl">Disease:</label><br>
                                    <select class="--input" v-model="formDisease.disease">
                                        <option :value="'Asthma'">
                                            Asthma
                                        </option>

                                        <option :value="'Cancer'">
                                            Cancer
                                        </option>
                                        
                                        <option :value="'Depression/Anxiety/Bipolar/Suicidal'">
                                            Depression/Anxiety/Bipolar/Suicidal
                                        </option>

                                        <option :value="'Diabetes'">
                                            Diabetes
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Emphysema (COPD)
                                        </option>

                                        <option :value="'Heart Disease'">
                                            Heart Disease
                                        </option>

                                        <option :value="'High Blood Pressure (hypertension)'">
                                            High Blood Pressure (hypertension)
                                        </option>

                                        <option :value="'High Cholesterol'">
                                            High Cholesterol
                                        </option>

                                        <option :value="'Hypothyroidism/Thyroid Disease'">
                                            Hypothyroidism/Thyroid Disease
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Renal (kidney) Disease
                                        </option>

                                        <option :value="'Stroke'">
                                           Stroke
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Renal (kidney) Disease
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('disease', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2" v-if="formDisease.other">
                                    <label class="text-bold text-xl">Disease:</label><br>
                                    <input type="text" v-model="formDisease.disease" placeholder="Disease" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('disease', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <input type="checkbox" v-model="formDisease.other">
                                    <label for="vehicle1"> Other</label><br>
                                </div>

                                <div class="w-full p-2" v-if="formDisease.disease == 'Cancer' || formDisease.type == 'Diabetes'">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <input type="text" v-model="formDisease.type" placeholder="Type" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Status:</label><br>
                                    <select class="--input" v-model="formDisease.status">
                                        <option :value="'Current'">
                                            Current
                                        </option>

                                        <option :value="'Past'">
                                            Past
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('status', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Comment:</label><br>
                                    <input type="text" v-model="formDisease.comment" placeholder="Comment" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('comment', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveDisease()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="disease"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['DISEASE', 'TYPE', 'STATUS', 'COMMENT']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.diseases" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'disease'}, {label: 'type'}, {label: 'status'}, {label: 'comment'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.diseases[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeDisease ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['DISEASE', 'TYPE', 'STATUS', 'COMMENT']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.diseases" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'disease'}, {label: 'type'}, {label: 'status'}, {label: 'comment'}]" :key="i" class="cursor-pointer"
                                            @click="formDisease = Object.assign({}, l); activeDisease = true"
                                        >
                                            <span>{{options.diseases[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10 ml-2">
                            SURGERIES
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeSurgery" @click="activeSurgery = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeSurgery" @click="activeSurgery = false; formSurgery.id = null; formSurgery.type = null; formSurgery.date = null; formSurgery.facility - null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('surgery')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row" >
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeSurgery">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Type (specify left/right):</label><br>
                                    <input type="text" v-model="formSurgery.type" placeholder="Type" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date:</label><br>
                                    <input type="date" v-model="formSurgery.date" placeholder="Date" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('date', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">LOCATION/FACILITY:</label><br>
                                    <input type="text" v-model="formSurgery.facility" placeholder="LOCATION/FACILITY" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('facility', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveSurgery()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="surgery"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['TYPE', 'DATE', 'LOCATION/FACILITY']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.surgeries" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}, {label: 'facility'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.surgeries[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeSurgery ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['TYPE', 'DATE', 'LOCATION/FACILITY']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.surgeries" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'date'}, {label: 'facility'}]" :key="i" class="cursor-pointer"
                                            @click="formSurgery = Object.assign({}, l); activeSurgery = true"
                                        >
                                            <span>{{options.surgeries[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10 ml-2" v-if="options.gender != 'Male'">
                            WOMEN’S HEALTH HISTORY
                        </p>

                        <div class="w-full mb-2" v-if="options.gender != 'Male'">
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeWomen" @click="activeWomen = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeWomen" @click="activeWomen = false; formWomen.id = null; formWomen.dlmc = null; formWomen.tnp = null; formWomen.complications = null; formWomen.afm = null; formWomen.am = null; formWomen.nlb = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('women')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row" v-if="options.gender != 'Male'">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeWomen">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Date of Last Menstrual Cycle:</label><br>
                                    <input type="date" v-model="formWomen.dlmc" placeholder="Date of Last Menstrual Cycle:" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('dlmc', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Total Number of Pregnancy:</label><br>
                                    <input type="text" v-model="formWomen.tnp" placeholder="Total Number of Pregnancy" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('tnp', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Pregnancy Complications:</label><br>
                                    <input type="text" v-model="formWomen.complications" placeholder="Pregnancy Complications" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('complications', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Age of First Menstruation:</label><br>
                                    <input type="text" v-model="formWomen.afm" placeholder="Age of First Menstruation" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('afm', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Age of Menopause:</label><br>
                                    <input type="text" v-model="formWomen.am" placeholder="Number of Live Births" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('am', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Number of Live Births:</label><br>
                                    <input type="text" v-model="formWomen.nlb" placeholder="Pregnancy Complications" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('nlb', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveWomen()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="women"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['Date of Last Menstrual Cycle', 'Total Number of Pregnancies', 'Pregnancy Complications', 'Age of First Menstruation', 'Age of Menopause', 'Number of Live Births']" :key="column" style="text-transform: capitalize;" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.womens" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'dlmc'}, {label: 'tnp'}, {label: 'complications'}, {label: 'afm'}, {label: 'am'}, {label: 'nlb'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.womens[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeWomen ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['Date of Last Menstrual Cycle', 'Total Number of Pregnancies', 'Pregnancy Complications', 'Age of First Menstruation', 'Age of Menopause', 'Number of Live Births']" :key="column" style="text-transform: capitalize;">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.womens" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'dlmc'}, {label: 'tnp'}, {label: 'complications'}, {label: 'afm'}, {label: 'am'}, {label: 'nlb'}]" :key="i" class="cursor-pointer"
                                            @click="formWomen = Object.assign({}, l); activeWomen = true"
                                        >
                                            <span>{{options.womens[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <p class="text-2xl mt-10 ml-2">
                            RISKY HABITS
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeHabits" @click="activeHabits = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeHabits" @click="activeHabits = false; formHabits.id = null; formHabits.type = null; formHabits.status = null;"> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('habit')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeHabits">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Risky Habit:</label><br>
                                    <select class="--input" v-model="formHabits.type">
                                        <option :value="'Cigarette'">
                                            Cigarette
                                        </option>

                                        <option :value="'Alcohol'">
                                            Alcohol
                                        </option>

                                        <option :value="'Drugs'">
                                            Drugs
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Status:</label><br>
                                    <select class="--input" v-model="formHabits.status">
                                        <option :value="'Past'">
                                            Past
                                        </option>

                                        <option :value="'Current'">
                                            Current
                                        </option>
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('status', saveError)}} </span> 
                                </div>


                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveHabit()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false"
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="habit"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['RISKY HABIT', 'STATUS']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.habits" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'type'}, {label: 'status'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.habits[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf>

                            <div :style="{width: activeDisease ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['RISKY HABIT', 'STATUS']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.habits" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'type'}, {label: 'status'}]" :key="i" class="cursor-pointer"
                                            @click="formHabits = Object.assign({}, l); activeHabits = true"
                                        >
                                            <span>{{options.habits[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>


                    </div>
                </div>

                <!--  -->


                <div class="w-full flex flex-col p-4" v-if="activeTab == 'family'">
                    <div class="w-full flex flex-col">
                        <p class="text-2xl mt-10 ml-2">
                            DISEASE HISTORY
                        </p>

                        <div class="w-full mb-2" >
                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="!activeDisease" @click="activeDisease = true"> 
                                <i class="fa-solid fa-plus px-2"></i>
                            </span>

                            <span class="float-right cursor-pointer text-xl" style="border: 1px solid black" v-if="activeDisease" @click="activeDisease = false; formDisease.id = null; formDisease.family = null; formDisease.disease = null; formDisease.other = null; formDisease.status = null; formDisease.comment = null; "> 
                                <i class="fa-solid fa-xmark px-2"></i>
                            </span>

                            <span class="float-right text-xl mx-1 cursor-pointer" style="border: 1px solid black;" @click="printReport('family')">
                                <i class="fa-solid fa-print px-2"></i>
                            </span>
                        </div>

                        <div class="w-full flex flex-row">
                            <div style="width: 30%; border: 1px solid black; border-radius: 5px" class="w-full flex flex-col" v-if="activeDisease">
                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Family Member:</label><br>
                                    <select class="--input" v-model="formDisease.family">
                                        <option :value="'Mother'">
                                            Mother
                                        </option>

                                        <option :value="'Father'">
                                            Father
                                        </option>

                                        <option :value="'Brother'">
                                            Brother
                                        </option>

                                        <option :value="'Sister'">
                                            Sister
                                        </option>

                                        <option :value="'Grandmother'">
                                            Grandmother
                                        </option>

                                        <option :value="'Grandfather'">
                                            Grandfather
                                        </option>

                                        <option :value="'Uncle'">
                                            Uncle
                                        </option>

                                        <option :value="'Auntie'">
                                            Auntie
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('status', saveError)}} </span> 
                                </div>


                                <div class="w-full p-2" v-if="!formDisease.other">
                                    <label class="text-bold text-xl">Disease:</label><br>
                                    <select class="--input" v-model="formDisease.disease">
                                        <option :value="'Asthma'">
                                            Asthma
                                        </option>

                                        <option :value="'Cancer'">
                                            Cancer
                                        </option>
                                        
                                        <option :value="'Depression/Anxiety/Bipolar/Suicidal'">
                                            Depression/Anxiety/Bipolar/Suicidal
                                        </option>

                                        <option :value="'Diabetes'">
                                            Diabetes
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Emphysema (COPD)
                                        </option>

                                        <option :value="'Heart Disease'">
                                            Heart Disease
                                        </option>

                                        <option :value="'High Blood Pressure (hypertension)'">
                                            High Blood Pressure (hypertension)
                                        </option>

                                        <option :value="'High Cholesterol'">
                                            High Cholesterol
                                        </option>

                                        <option :value="'Hypothyroidism/Thyroid Disease'">
                                            Hypothyroidism/Thyroid Disease
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Renal (kidney) Disease
                                        </option>

                                        <option :value="'Stroke'">
                                           Stroke
                                        </option>

                                        <option :value="'Emphysema (COPD)'">
                                            Renal (kidney) Disease
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('disease', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2" v-if="formDisease.other">
                                    <label class="text-bold text-xl">Disease:</label><br>
                                    <input type="text" v-model="formDisease.disease" placeholder="Disease" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('disease', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <input type="checkbox" v-model="formDisease.other">
                                    <label for="vehicle1"> Other</label><br>
                                </div>

                                <div class="w-full p-2" v-if="formDisease.disease == 'Cancer' || formDisease.type == 'Diabetes'">
                                    <label class="text-bold text-xl">Type:</label><br>
                                    <input type="text" v-model="formDisease.type" placeholder="Type" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('type', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Status:</label><br>
                                    <select class="--input" v-model="formDisease.status">
                                        <option :value="'Current'">
                                            Current
                                        </option>

                                        <option :value="'Past'">
                                            Past
                                        </option>
                                        
                                    </select>
                                    <span class="text-xs text-red-500 ml-2">{{validationError('status', saveError)}} </span> 
                                </div>

                                <div class="w-full p-2">
                                    <label class="text-bold text-xl">Comment:</label><br>
                                    <input type="text" v-model="formDisease.comment" placeholder="Comment" class="--input">
                                    <span class="text-xs text-red-500 ml-2">{{validationError('comment', saveError)}} </span>
                                </div>

                                <div class="w-full p-2">
                                    <button class="w-full py-2 text-white" style="background: #366422; border-radius: 10px"
                                        @click="saveDisease()"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <VueHtml2pdf
                                :show-layout="false"
                                :float-layout="true"
                                :enable-download="true"
                                :preview-modal="true"
                                :paginate-elements-by-height="1000"
                                :filename="Math.random().toString(36).slice(2)"
                                :pdf-quality="2"
                                :manual-pagination="false" 
                                pdf-format="a4"
                                pdf-orientation="landscape"
                                pdf-content-width="100%"
                                ref="family"
                            >
                                <section slot="pdf-content">
                                    <div class="w-full p-5">
                                        <table class="w-full --table">
                                            <tr class="text-center">
                                                <th v-for="column in ['Family Member','DISEASE', 'TYPE', 'STATUS', 'COMMENT']" :key="column" class="--th">
                                                    {{ column }}
                                                </th>
                                            </tr>

                                            <tr class="text-center"
                                                v-for="(l, index) in options.familyDiseases" :key="index"
                                            >
                                                <td v-for="(k, i) in [{label: 'family'}, {label: 'disease'}, {label: 'type'}, {label: 'status'}, {label: 'comment'}]" :key="i" class="cursor-pointer --td">
                                                    <span>{{options.familyDiseases[index][k.label] }}</span> 
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </section>
                            </VueHtml2pdf> 

                            <div :style="{width: activeDisease ? '70%' : '100%'}" class="w-full flex flex-col ml-2">
                                <table class="w-full">
                                    <tr class="text-center">
                                        <th v-for="column in ['Family Member','DISEASE', 'TYPE', 'STATUS', 'COMMENT']" :key="column">
                                            {{ column }}
                                        </th>
                                    </tr>

                                    <tr class="text-center"
                                        v-for="(l, index) in options.familyDiseases" :key="index"
                                    >
                                        <td v-for="(k, i) in [{label: 'family'}, {label: 'disease'}, {label: 'type'}, {label: 'status'}, {label: 'comment'}]" :key="i" class="cursor-pointer"
                                            @click="formDisease = Object.assign({}, l); activeDisease = true"
                                        >
                                            <span>{{options.familyDiseases[index][k.label] }}</span> 
                                        </td>
                                    </tr>

                                </table>
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
import VueHtml2pdf from 'vue-html2pdf'

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle,
        Table,
        VueHtml2pdf
    },

    data(){
        return {
            activeTab: 'personal',
            formAllergy: {
                id: null,
                patient_id: this.options.patient,
                allergy: null,
                allergic_reaction: null
            },
            formMedication: {
                id: null,
                patient_id: this.options.patient,
                medications: null,
                dose: null,
                times_per_day: null
            },
            formMaintenance: {
                id: null,
                patient_id: this.options.patient,
                type: null,
                date: null,
                facility: null,
                abnormal_result: null
            },
            formVaccination: {
                id: null,
                patient_id: this.options.patient,
                type: null,
                date: null
            },
            formDisease: {
                id: null,
                patient_id: this.options.patient,
                disease: null,
                type: null,
                status: null,
                comment: null,
                other: null,
                family: null
            },
            formSurgery: {
                id: null,
                patient_id: this.options.patient,
                type: null,
                date: null,
                facility: null
            },
            formWomen: {
                id: null,
                patient_id: this.options.patient,
                dlmc: null,
                tnp: null,
                complications: null,
                afm: null,
                am: null,
                nlb: null
            },
            formHabits: {
                id: null,
                patient_id: this.options.patient,
                type: null,
                status: null
            },
            saveError: null,
            activeAllergy: false,
            activeMedication: false,
            activeMaintenance: false,
            activeVaccination: false,
            activeDisease: false,
            activeSurgery: false,
            activeWomen: false,
            activeHabits: false
        }
    },

    watch: {
        activeTab(arg) {
            
        },

        'formDisease.other'(arg) {
            this.formDisease.disease = null
        }
    },

    mounted(){
    },

    methods: {
        printReport(arg){
            this.$refs[arg].generatePdf()
        },
        saveAllergy() {
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-allergy', this.formAllergy)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                } 
            });
        },

        saveMedication() {
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-medication', this.formMedication)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                } 
            });
        },

        saveMaintenance() {
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-maintenance', this.formMaintenance)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                } 
            });
        },

        saveVaccination(){
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-vaccination', this.formVaccination)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                } 
            });
        },

        saveDisease(){
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-disease', this.formDisease)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                }
            });
        },
        saveSurgery(){
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-surgery', this.formSurgery)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                }
            });
        },
        saveWomen() {
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-women', this.formWomen)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
                                    icon: "success",
                                    button: "Okay",
                                });

                                location.reload()
                            }
                        })
                }
            });
        },

        saveHabit() {
            swal({
                title: "Are you sure to save this history?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((procceed) => {
                if (procceed) {
                    axios.post(this.$root.route + '/history/save-habit', this.formHabits)
                        .then(response => {
                            if(response.data.status == 422) {
                                this.saveError = response.data.errors 
                            } else {
                                swal({
                                    title: "Good job!",
                                    text: "You successfuly save this history",
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

.--table {
    border-collapse: collapse;
    border-radius: 5px;
    border-style: hidden;
    box-shadow: 0 0 0 1px black;
}

.--td {
    border: 1px solid black;
    padding-top: 20px;
    padding-bottom: 20px;
}

.--th {
    border: 1px solid black;
    background: #366422;
    color: #ffffff;
    padding-top: 20px;
    padding-bottom: 20px;
}

input[type=text] {
    text-transform: capitalize;
}

</style>
