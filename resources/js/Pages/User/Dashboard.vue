<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import ChartPertumbuhanPengguna from '@/Components/Chart/ChartPertumbuhanPengguna.vue';
import CardCalendar from '@/Components/Card/CardCalendar.vue';
import ChartJumlah from '@/Components/Chart/ChartJumlah.vue';
import HeaderStats from '@/Components/Header/HeaderStats.vue';
import { defineProps } from "vue";

const props = defineProps({
    pengguna: Number,
    balita: Number,

});
</script>

<template>

    <Head title="Dashboard Pasien" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Pasien</h2>
        </template>


    </UserLayout>
</template>
