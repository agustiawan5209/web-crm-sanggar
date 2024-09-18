<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import ChartJenisImunisasi from '@/Components/Chart/ChartJenisImunisasi.vue';
import TransactionCardTable from '@/Components/Card/TransactionCardTable.vue';
import Dropdown from '@/Components/Dropdown.vue';
import ProdukCard from '@/Components/Product/ProdukCard.vue';

import { ref, watch, defineProps } from 'vue';
const props = defineProps({
    search: {
        type: String,
        default: '',
    },
    order: {
        type: String,
        default: '',
    },
    data: {
        type: Object,
        default: () => ({}),
    },
    table_colums: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

const crud = ref({
    tambah: props.can.add,
    edit: props.can.edit,
    show: props.can.show,
    delete: props.can.delete,

})

</script>

<template>

    <Head title="Penyewaan" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Penyewaan</h2>
        </template>

        <div class="py-4 relative box-content">
            <div class="grid md:grid-cols-3" v-if="data.data.length > 0">
                <template v-for="item in data.data">
                    <ProdukCard :produk='item'></ProdukCard>
                </template>

            </div>
            <div class="container w-full text-center text-base mx-auto py-2" v-else>
                <h4>DATA KOSONG</h4>
            </div>
        </div>
    </UserLayout>
</template>
