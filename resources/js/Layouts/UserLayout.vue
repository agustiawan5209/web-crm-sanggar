<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import HomeLayout from '@/Layouts/HomeLayout.vue';
import ProfileEdit from '@/Pages/User/Profile/Edit.vue'
const User = usePage().props.auth.user

const active = "bg-teal-50 border-teal-500 text-teal-700 hover:bg-teal-50 hover:text-teal-700 group border-l-4 px-3 py-2 flex items-center text-sm font-medium";
const nonactive = "border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 px-3 py-2 flex items-center text-sm font-medium";
</script>

<template>
    <HomeLayout>
        <main class="relative pt-3 bg-sky-500">
            <div class="mx-auto max-w-full px-4 pb-6 sm:px-6 lg:px-4 ">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x">
                        <aside class="py-6 lg:col-span-2">
                            <nav class="space-y-1">

                                <Link :href="route('Customer.dashboard')" :class="route().current('Customer.dashboard') ? active : nonactive">
                                   <font-awesome-icon :icon="['fas','home']" class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                                    <span class="truncate">Dashboard</span>
                                </Link>
                                <Link :href="route('Customer.profile')" :class="route().current('Customer.profile') ? active : nonactive">
                                   <font-awesome-icon :icon="['fas','circle-user']" class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                                    <span class="truncate">Profile</span>
                                </Link>

                                <Link :href="route('Customer.penyewaan')" :class="route().current('Customer.penyewaan') ? active : nonactive">
                                    <font-awesome-icon :icon="['fas','credit-card']" class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                                     <span class="truncate">Data Pemesanan</span>
                                 </Link>
                                <Link :href="route('Customer.Pembayaran.index')" :class="route().current('Customer.Pembayaran.index') ? active : nonactive">
                                    <font-awesome-icon :icon="['fas','credit-card']" class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                                     <span class="truncate">Data Transaksi</span>
                                 </Link>
                                <Link :href="route('Customer.riwayat.penyewaan')" :class="route().current('Customer.riwayat.penyewaan') ? active : nonactive">
                                    <font-awesome-icon :icon="['fas','credit-card']" class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                                     <span class="truncate">Riwayat Pemesanan</span>
                                 </Link>

                            </nav>
                        </aside>

                        <div class="p-2 lg:col-span-10">
                            <slot/>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </HomeLayout>
</template>
