<template>
    <!-- Hero Section -->
    <section v-if="items && items.file_video"
        class="relative h-screen bg-gray-900 text-white flex items-center justify-center">
        <!-- Background Video -->
        <video v-if="items.file_video" autoplay loop muted playsinline @canplay="onVideoCanPlay" @error="onVideoError"
            class="absolute inset-0 w-full h-full object-cover opacity-60">
            <source :src="items.file_video" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Overlay Layer (Gradient) -->
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black opacity-80"></div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center max-w-2xl px-4">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up">
                <span class="text-yellow-400"> {{ items.title }} </span>
            </h1>
            <p class="text-lg md:text-xl mb-8 animate-fade-in-up delay-200" v-html="items.description">
            </p>
            <Link :href="route('all.produk_jasa')"
                class="bg-yellow-400 text-gray-900 font-bold py-3 px-6 rounded-lg hover:bg-yellow-300 transition duration-300 animate-fade-in-up delay-400">
                Mulai Menjelajah
            </Link>
        </div>
    </section>
</template>

<script setup>
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

import 'vue3-carousel/dist/carousel.css'

const items = ref(null) // Awalnya null untuk memastikan ada data yang ditunggu
const currentIndex = ref(0)

onMounted(async () => {
    try {
        const response = await axios.get(route('information.all.data'))
        items.value = response.data // Pastikan struktur data sesuai
    } catch (error) {
        console.error('Error fetching data:', error)
    }
})

// Fungsi video
const onVideoCanPlay = (event) => {
    event.target.play(); // Memastikan autoplay berjalan
};
const onVideoError = (event) => {
    console.error('Video failed to load:', event);
};
</script>
