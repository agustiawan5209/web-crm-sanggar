<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head ,Link, usePage } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import ChartTransaksiTahun from '@/Components/Chart/ChartTransaksiTahun.vue';
import CardCalendar from '@/Components/Card/CardCalendar.vue';

const props = defineProps({
    penyewaan: {
        type: Object,
        default: () => ({}),
    },
    produk: {
        type: Object,
        default: () => ({}),
    },
    transaksi: {
        type: Object,
        default: () => ({}),
    },
})

const Page = usePage().props.auth;
const Roles = Page.role;

function roleToCheck(role) {
    if (Array.isArray(Roles)) {
        return Roles.includes(role)
    } else {
        return false;
    }
}

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Dashboard
        </template>

        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                <slot name="header" />
            </h2>

            <div class="container items-center px-4 py-2 m-auto mt-5">
                <div class="flex flex-wrap pb-3 mx-4 ">
                    <!-- <ul
                            class="w-full sm:w-4/5 text-xs sm:text-sm justify-center lg:justify-end items-center flex flex-row space-x-1 mt-6 overflow-hidden mb-4">
                            <li><button
                                    class="px-4 py-2 bg-indigo-500 rounded-full text-sm text-gray-100 hover:bg-indigo-700 hover:text-gray-200">30
                                    days</button></li>
                            <li><button
                                    class="px-4 py-2 bg-gray-200 rounded-full text-sm text-gray-700 hover:bg-indigo-700 hover:text-gray-200">90
                                    days</button></li>
                            <li><button
                                    class="px-4 py-2 bg-gray-200 rounded-full text-sm text-gray-700 hover:bg-indigo-700 hover:text-gray-200">6
                                    months</button></li>
                            <li><button
                                    class="px-4 py-2 bg-gray-200 rounded-full text-sm text-gray-700 hover:bg-indigo-700 hover:text-gray-200">12
                                    months</button></li>
                        </ul> -->
                    <!-- <div class="w-full p-2 lg:w-1/4 md:w-1/2">
                        <Link :href="route('Penyewaan.index')"
                            class="flex flex-col px-6 py-10 overflow-hidden bg-white hover:bg-gradient-to-br hover:from-purple-400 hover:via-blue-400 hover:to-blue-500 rounded-xl shadow-lg duration-300 hover:shadow-2xl group">
                            <div class="flex flex-row justify-between items-center">
                                <div class="px-4 py-4 bg-gray-300  rounded-xl bg-opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-gray-50"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                        <path fill-rule="evenodd"
                                            d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>

                            </div>
                            <h1
                                class="text-3xl sm:text-4xl xl:text-5xl font-bold text-gray-700 mt-12 group-hover:text-gray-50">
                                {{ penyewaan.count }}</h1>
                            <div class="flex flex-row justify-between group-hover:text-gray-200">
                                <p>Jumlah Penyewaan</p>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-indigo-600 group-hover:text-gray-200" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </Link>
                    </div> -->
                    <!-- <div class="w-full p-2 lg:w-1/4 md:w-1/2">
                        <div
                            class="flex flex-col px-6 py-10 overflow-hidden bg-white hover:bg-gradient-to-br hover:from-purple-400 hover:via-blue-400 hover:to-blue-500 rounded-xl shadow-lg duration-300 hover:shadow-2xl group">
                            <div class="flex flex-row justify-between items-center">
                                <div class="px-4 py-4 bg-gray-300  rounded-xl bg-opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-gray-50"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                    </svg>
                                </div>

                            </div>
                            <h1
                                class="text-3xl sm:text-4xl xl:text-5xl font-bold text-gray-700 mt-12 group-hover:text-gray-50">
                                {{ produk.jasa }}</h1>
                            <div class="flex flex-row justify-between group-hover:text-gray-200">
                                <p>Jumlah Paket</p>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-indigo-600 group-hover:text-gray-200" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="w-full p-2 lg:w-1/4 md:w-1/2">
                        <div
                            class="flex flex-col px-6 py-10 overflow-hidden bg-white hover:bg-gradient-to-br hover:from-purple-400 hover:via-blue-400 hover:to-blue-500 rounded-xl shadow-lg duration-300 hover:shadow-2xl group">
                            <div class="flex flex-row justify-between items-center">
                                <div class="px-4 py-4 bg-gray-300  rounded-xl bg-opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-gray-50"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>

                            </div>
                            <h1
                                class="text-3xl sm:text-4xl xl:text-5xl font-bold text-gray-700 mt-12 group-hover:text-gray-50">
                                {{ produk.alat }}</h1>
                            <div class="flex flex-row justify-between group-hover:text-gray-200">
                                <p>Produk Alat</p>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-indigo-600 group-hover:text-gray-200" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="w-full p-2 lg:w-1/4 md:w-1/2" v-if="roleToCheck('Bendahara')">
                        <Link :href="route('Riwayat.index')"
                            class="flex flex-col px-6 py-10 overflow-hidden bg-white hover:bg-gradient-to-br hover:from-purple-400 hover:via-blue-400 hover:to-blue-500 rounded-xl shadow-lg duration-300 hover:shadow-2xl group">
                            <div class="flex flex-row justify-between items-center">
                                <div class="px-4 py-4 bg-gray-300  rounded-xl bg-opacity-30">
                                    <font-awesome-icon :icon="['fas', 'credit-card']" />
                                </div>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-700 mt-12 group-hover:text-gray-50">
                                {{ transaksi.pendapatan }}
                            </h1>
                            <div class="flex flex-row justify-between group-hover:text-gray-200">
                                <p>Total Pendapatan</p>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-indigo-600 group-hover:text-gray-200" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </Link>
                    </div> -->
                </div>
                <div class="relative w-full">
                    <h3 class=" text-xs md:text-base lg:text-lg font-semibold text-center">Kalender Jadwal Penyewaan</h3>
                    <CardCalendar />
                </div>
                <div class="bg-white">
                    <ChartTransaksiTahun></ChartTransaksiTahun>
                </div>
            </div>


        </div>
    </AuthenticatedLayout>
</template>
