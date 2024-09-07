<script setup>
import { Head, Link } from '@inertiajs/vue3';
import HomeLayout from '@/Layouts/HomeLayout.vue';
import Pricing from '@/Components/Product/Pricing.vue'
import Carousel from '@/Components/Carousel.vue'
import { defineProps } from 'vue';

const props = defineProps({
    jasa: {
        type: Object,
        default: () => ({}),
    },
})
</script>

<template>

    <Head title="Welcome" />
    <HomeLayout class="w-full">
        <!-- start hero -->
        <Carousel></Carousel>
        <div class="bg-gray-100 block">
            <div class="bg-white dark:bg-gray-800 flex relative z-20 items-center overflow-hidden">
                <div class="container mx-auto px-6 flex relative py-16">
                    <div class="sm:w-2/3 lg:w-2/5 flex flex-col relative z-20">
                        <span class="w-20 h-2 bg-gray-800 dark:bg-white mb-12">
                        </span>
                        <h1
                            class="font-bebas-neue uppercase text-6xl sm:text-8xl font-black flex flex-col leading-none dark:text-white text-gray-800">
                            Sanggar Seni
                            <span class="text-5xl sm:text-7xl">
                                Kawali
                            </span>
                        </h1>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-white">
                            Sanggar Seni Kawali merupakan wadah penyaluran minat di bidang
                            seni yang berada di Kabupaten Soppeng. Pada sanggar ini menawarkan
                            penyewaan paket jasa pementasan tari, pelatihan tari, penyewaan paket kostum
                            tari dan kostum pakaian adat.
                        </p>
                        <!-- <div class="flex mt-8">
                            <a href="#" class="uppercase py-2 px-4 rounded-lg bg-pink-500 border-2 border-transparent text-white text-md mr-4 hover:bg-pink-400">
                                Get started
                            </a>
                            <a href="#" class="uppercase py-2 px-4 rounded-lg bg-transparent border-2 border-pink-500 text-pink-500 dark:text-white hover:bg-pink-500 hover:text-white text-md">
                                Read more
                            </a>
                        </div> -->
                    </div>
                    <div class="hidden sm:block sm:w-1/3 lg:w-3/5 relative">
                        <img :src="'img/Tarian_makassar_pakarenaa.png'" class="max-w-xs md:max-w-sm m-auto" />
                    </div>
                </div>
            </div>
        </div>
        <!-- end hero -->

        <!-- start Pricing -->
        <section class="relative border-t-4 border-white">
            <Pricing :jasa="jasa.data"></Pricing>
        </section>
        <!-- end Pricing -->

        <!-- start testimonials -->
        <!-- <section class="relative bg-gray-800">
            <ProductCollection></ProductCollection>
        </section> -->
        <!-- end testimonials -->



    </HomeLayout>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
