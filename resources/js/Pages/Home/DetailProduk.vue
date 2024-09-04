<script setup>
import { defineProps, ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import HomeLayout from '@/Layouts/HomeLayout.vue';
import Paket from '@/Components/Product/Paket.vue';
import PaketAlat from '@/Components/Product/PaketAlat.vue';

const props = defineProps({
    produk: {
        type: Object,
        default: () => ({}),
    },
    tipe: {
        type: String,
        default: '',
    },
});

const currentImage = ref(null);
const images = [];

if (props.produk.image != null) {
    for (let i = 0; i < props.produk.image.length; i++) {
        const element = props.produk.image[i];
        images[i] = element.image_url;

        if (element.status) {
            currentImage.value = element.image_url;
        }
    }
}

const fabricColors = ['#f0f0f0', '#d9b3b3', '#800000', '#ff6600'];
const legFinishes = ['#a52a2a', '#d2b48c', '#000000'];

const selectedFabricColor = ref(fabricColors[0]);
const selectedLegFinish = ref(legFinishes[0]);

const upgrades = ref({
    lumbarPillows: false,
    fancyMetals: false
});

const quantity = ref(1);

const increaseQuantity = () => {
    quantity.value++;
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};
</script>

<template>

    <Head title="Produk" />
    <HomeLayout>
        <div class="max-w-4xl mx-auto px-4 py-4 md:py-12">
            <div class="flex flex-col p-2 lg:flex-row bg-white shadow-md rounded-lg overflow-hidden">
                <div class="flex-shrink-0">
                    <transition name="fade" mode="out-in">
                        <img v-if="currentImage" :key="currentImage" class="w-full h-96 object-cover lg:h-auto lg:w-96"
                            :src="currentImage" :alt="produk.nama" />
                    </transition>
                    <div class="flex justify-center space-x-2 mt-4">
                        <img v-for="(image, index) in images" :key="index" :src="image" @click="currentImage = image"
                            class="w-16 h-16 object-cover cursor-pointer border-2"
                            :class="{ 'border-blue-500': currentImage === image, 'border-gray-300': currentImage !== image }" />
                    </div>
                </div>
                <div class="p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">{{ produk.nama }}</h2>
                        <p class="text-gray-700 mb-4">{{ produk.rupiah }}</p>
                        <p class="text-gray-500 mb-4" v-html="produk.keterangan"></p>
                        <div class="flex items-center mb-4">
                            <span class="text-yellow-400">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                            <span class="text-gray-600 ml-2">(14 reviews)</span>
                        </div>
                        <div class="mb-4">
                            <span class="block mb-1 font-medium" v-if="tipe=='alat'">Stok Produk:{{ produk.stok }}</span>

                        </div>
                        <div class="mb-4 flex items-center" v-if="tipe =='alat'">
                            <button @click="decreaseQuantity"
                                class="bg-gray-300 text-gray-700 px-2 py-1 rounded-l-lg hover:bg-gray-400 transition duration-300">-</button>
                            <input type="text" readonly v-model="quantity" min="1" :max="produk.stok"
                                class="text-center w-12 border-t border-b border-gray-300 focus:outline-none">
                            <button @click="increaseQuantity"
                                class="bg-gray-300 text-gray-700 px-2 py-1 rounded-r-lg hover:bg-gray-400 transition duration-300">+</button>
                        </div>
                    </div>
                    <Link v-if="tipe == 'alat'" :href="route('payment.list.alat', {slug:produk.id, quantity: quantity})" class="bg-blue-600 text-center text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-700">Sewa Produk</Link>
                    <Link v-if="tipe == 'jasa'" :href="route('payment.list', {slug:produk.id})" class="bg-blue-600 text-center text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-700">Sewa Produk</Link>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.form-checkbox:checked {
    border-color: #1c64f2;
    background-color: #1c64f2;
}
</style>
