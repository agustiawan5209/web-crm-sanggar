<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import HomeLayout from '@/Layouts/HomeLayout.vue';

</script>

<template>
    <HomeLayout>

        <div class="bg-gray-100">
            <section class="cover bg-blue-teal-gradient relative bg-blue-600 px-4 sm:px-8 lg:px-16 xl:px-40 2xl:px-64 overflow-hidden py-24 flex
      items-center min-h-full justify-center">

                <div class="bg-white w-full md:w-[600px] p-4 rounded-lg z-50">
                <slot/>

                </div>
            </section>
        </div>
    </HomeLayout>
</template>
