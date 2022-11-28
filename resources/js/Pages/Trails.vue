<template>
     <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
            <div style="width: 100%" class="mt-5">
                <Table :columns="columns" :rows="options.logs" :keys="keys" :selected.sync="selected"/>
            </div>
        </div>
    </Navigation>
</template>

<script>
import Navigation from '../Layouts/Sidebar.vue'
import Table from "../Components/Table";
import Toggle from '../Components/Toggle.vue';
import axios from "axios";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table,
        Toggle
    },
    data(){
        return {
            selected: null,
            columns: ['Logs', 'Date'],
            keys: [
                {
                    label: 'description'
                },
                {
                    label: 'date'
                }
            ]
        }
    },

    created(){
        console.log(this.options)
    },

    methods: {

    }
}
</script>

<style scoped>

</style>