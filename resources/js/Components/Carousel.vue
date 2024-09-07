<template>
    <Carousel :autoplay="5000" :wrap-around="true">
        <Slide v-for="(item, index) in items" :key="index" :class="{ 'active': index === currentIndex }">
            <div class="relative w-full h-[500px]">
                <div class="absolute w-full h-full left-0 top-0 bg-gray-800 opacity-25"></div>
                <img :src="item.gambar" class="block w-full h-full object-cover" alt="..." />
                <div class="absolute w-full h-full flex flex-col justify-center items-center bottom-0 left-0 p-4 text-white">
                    <h5 class="text-xl">{{ item.title }}</h5>
                    <p v-html="item.description"></p>
                </div>
            </div>
        </Slide>

        <template #addons>
            <Navigation />
            <Pagination />
        </template>
    </Carousel>
</template>

<script setup>
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import { ref, onMounted } from 'vue';
import axios from 'axios';

import 'vue3-carousel/dist/carousel.css'

const items = ref([])
const currentIndex = ref(0)

onMounted(async () => {
    const response = await axios.get(route('information.all.data'))
    console.log(response)
    items.value = response.data
})
</script>

<style>
.carousel__item {
    min-height: 500px;
    width: 100%;
    background-color: #fff;
    color: #333;
    font-size: 20px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.carousel__slide {
    padding: 10px;
    position: relative;
}

.carousel__prev,
.carousel__next {
    box-sizing: content-box;
    border: none;
    background-color: transparent;
    padding: 10px;
    cursor: pointer;
}

.carousel__prev:hover,
.carousel__next:hover {
    background-color: #ddd;
}

.carousel__prev::before,
.carousel__next::before {
    font-size: 24px;
    color: #333;
}


</style>
