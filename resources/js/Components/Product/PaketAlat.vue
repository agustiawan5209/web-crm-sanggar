<script setup>
import { defineProps, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Modal from '../Modal.vue';

const props = defineProps(['alat', 'tipe']);
const ShowModal = ref(false);
const Form = useForm({
    slug: null,
});
const funModal = (id) => {
    ShowModal.value = true;
    Form.slug = id;
};

const addToCart = (id) => {
    // Implementasikan logika untuk menambah item ke keranjang
    console.log(`Item with id ${id} added to cart`);
};

const rentItem = (id) => {
    // Implementasikan logika untuk menyewa item
    console.log(`Item with id ${id} rented`);
};
</script>

<template>
    <div class="mx-auto grid max-w-md grid-cols-1 gap-8 lg:max-w-7xl lg:grid-cols-3 lg:gap-8">
        <div v-for="item in alat" :key="item.id"
            class="relative flex flex-col rounded-3xl overflow-hidden bg-gray-900 shadow-xl ring-1 ring-black/10">

            <Link :href="route('produk.detail', { tipe: tipe, slug: item.id })" class="group block ">
            <span
                class="absolute -right-px -top-px rounded-bl-3xl rounded-tr-3xl bg-rose-600 px-6 py-4 font-medium uppercase tracking-widest text-white">
                Save 10%
            </span>
            <template v-for="image in item.image">
                <img v-if="image.status" :src="image.image_url" alt=""
                    class="h-[350px] w-full object-cover transition duration-500 group-hover:scale-105 sm:h-[450px]" />
            </template>

            <div class="relative bg-gray-700 pl-3 pt-3">
                <h3 class="text-base text-gray-100 group-hover:underline group-hover:underline-offset-4">
                    {{ item.nama }}
                </h3>

                <p class="mt-2">
                    <span class="sr-only"> Harga </span>
                    <span class="tracking-wider text-gray-200"> {{ item.rupiah }} </span>
                </p>
            </div>
            </Link>
            <div class="flex">
                <button @click="rentItem(item.id)"
                    class="w-1/2 bg-transparent hover:bg-blue-900 transition-all duration-200 text-white px-2 py-2 text-center border rounded-bl-3xl">
                    Sewa
                </button>
                <button @click="addToCart(item.id)"
                    class="w-1/2 bg-transparent hover:bg-blue-900 transition-all duration-200 text-white px-2 py-2 text-center border rounded-br-3xl">
                    Keranjang
                </button>
            </div>
        </div>

    </div>
</template>
