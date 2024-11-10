<script setup>
import { Link, Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, defineProps, onMounted, watch } from 'vue';
import HomeLayout from '@/Layouts/HomeLayout.vue';
import ModalPayment from '@/Components/Payment/Modal.vue'
import axios from 'axios';
const dateNow = new Date().toLocaleDateString();
const TimeNow = new Date().toLocaleTimeString();

const props = defineProps({
    produk: {
        type: Object,
        default: () => ({}),
    },
    sewa: {
        type: Object,
        default: () => ({}),
    },
    tipe: {
        type: String,
        default: 'jasa',
    },
    quantity: {
        type: Number,
        default: 1,
    },
})
const JumlahDiskon = ref(0);
const User = usePage().props.auth.user;


const showModal = ref(false);

const funModal = () => {
    showModal.value = true
}
const closeModal = () => {
    showModal.value = false
}
const subTotal = ref(props.produk.harga * props.quantity)
const totalHarga = ref(props.produk.harga * props.quantity);
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}
const HargaDiskon = ref(0);
// watch(JumlahDiskon, (newValue) => {
//     HargaDiskon.value = subTotal * (JumlahDiskon.value / 100);
//     subTotal = subTotal - HargaDiskon.value;
// });

const getDiskon = async () => {
    try {
        const response = await axios.get(route('Api.diskon.get_diskon', { jumlah: props.quantity, user_id: User.id, tipe: props.tipe }));
        if (response.status == 200) {
            JumlahDiskon.value = response.data;
            HargaDiskon.value = subTotal.value * (JumlahDiskon.value / 100);

            subTotal.value = subTotal.value - HargaDiskon.value;
        }
    } catch (err) {
        console.error(err);
    }
}

onMounted(async () => {
    await getDiskon();
})
console.log(subTotal.value, HargaDiskon.value)
</script>

<template>

    <Head title="Checkout" />
    <ModalPayment :show="showModal" :sewa="sewa" :produk="produk" :jenisproduk="tipe" :quantity="quantity" :diskon="HargaDiskon"
        :subtotal="subTotal" maxWidth="2xl">
        <span class="absolute top-3 left-10 text-lg cursor-pointer" @click="showModal = false">X</span>

    </ModalPayment>
    <HomeLayout>
        <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
            <div class="flex justify-start item-start space-y-2 flex-col">
                <h1 class="text-3xl lg:text-4xl font-semibold leading-7 lg:leading-9 text-gray-800">Checkout Penyewaan
                </h1>
                <p class="text-base  font-medium leading-6 text-gray-600">{{ dateNow }} {{ TimeNow }}</p>
            </div>
            <div
                class="mt-10 flex flex-col xl:flex-row jusitfy-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
                <div class="flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8">
                    <div
                        class="flex flex-col justify-start items-start bg-primary px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
                        <div
                            class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full">
                            <div class="pb-4 md:pb-8 w-full md:w-40" v-for="image in produk.image">
                                <img class="w-full hidden md:block" :src="image.image_url" alt="dress"
                                    v-if="image.status" />
                            </div>
                            <div
                                class="border-b border-gray-200 md:flex-row flex-col flex justify-between items-start w-full pb-8 space-y-4 md:space-y-0">
                                <div class="w-full flex flex-col justify-start items-start space-y-8">
                                    <h3 class="text-xl text-white xl:text-2xl font-semibold leading-6 ">{{ produk.nama
                                        }}</h3>
                                    <div class="flex justify-start items-start flex-col space-y-2">
                                        <p v-html="produk.keterangan" class="text-white"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex justify-center md:flex-row flex-col items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8">
                        <div class="flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full  bg-primary space-y-6">
                            <h3 class="text-xl text-white font-semibold leading-5 ">Total Penyewaan</h3>
                            <div
                                class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
                                <div class="flex justify-between w-full">
                                    <p class="text-base text-white leading-4">Harga Produk</p>
                                    <p class="text-base text-gray-300 leading-4">{{ produk.rupiah }}</p>
                                </div>
                                <div class="flex justify-between w-full">
                                    <p class="text-base text-white leading-4">Jumlah Produk Yang Disewa</p>
                                    <p class="text-base text-gray-300 leading-4">{{ quantity }}</p>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <p class="text-base text-white leading-4">Discount
                                    </p>
                                    <p class="text-base text-gray-300 leading-4">{{ JumlahDiskon }}%</p>
                                </div>
                                <div class="flex justify-between items-center w-full">
                                    <p class="text-base text-white leading-4">Potongan</p>
                                    <p class="text-base text-gray-300 leading-4">{{ formatRupiah(HargaDiskon) }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center w-full">
                                <p class="text-base text-white font-semibold leading-4">Total</p>
                                <p class="text-base text-gray-300 font-semibold leading-4"> <i class="text-sm line-through"> {{ formatRupiah(totalHarga) }} </i> {{ formatRupiah(subTotal) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-primary w-full xl:w-96 flex justify-between items-center md:items-start px-4 py-6 md:p-6 xl:p-8 flex-col">
                    <h3 class="text-xl text-white font-semibold leading-5">Customer</h3>
                    <div
                        class="flex flex-col md:flex-row xl:flex-col justify-start items-stretch h-full w-full md:space-x-6 lg:space-x-8 xl:space-x-0">
                        <div class="flex flex-col justify-start items-start flex-shrink-0">
                            <div
                                class="flex justify-center w-full md:justify-start items-center space-x-4 py-8 border-b border-gray-200">
                                <img src="https://i.ibb.co/5TSg7f6/Rectangle-18.png" alt="avatar" />
                                <div class="flex justify-start items-start flex-col space-y-2">
                                    <p class="text-base text-white font-semibold leading-4 text-left">{{ User.name }}
                                    </p>
                                    <p class="text-sm text-gray-300 leading-5 ">10 Previous Orders</p>
                                </div>
                            </div>

                            <div
                                class="flex justify-center text-white md:justify-start items-center space-x-4 py-4 border-b border-gray-200 w-full">
                                <img class="hidden"
                                    src="https://tuk-cdn.s3.amazonaws.com/can-uploader/order-summary-3-svg1.svg"
                                    alt="email">
                                <img class="hidden md:block"
                                    src="https://tuk-cdn.s3.amazonaws.com/can-uploader/order-summary-3-svg1dark.svg"
                                    alt="email">
                                <p class="cursor-pointer text-sm leading-5 ">{{ User.email }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between xl:h-full items-stretch w-full flex-col mt-6 md:mt-0">
                            <div
                                class="flex justify-center md:justify-start xl:flex-col flex-col md:space-x-6 lg:space-x-8 xl:space-x-0 space-y-4 xl:space-y-12 md:space-y-0 md:flex-row items-center md:items-start">
                                <div
                                    class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4 xl:mt-8">
                                    <p class="text-base text-white font-semibold leading-4 text-center md:text-left">
                                        Shipping Address</p>
                                    <p
                                        class="w-48 lg:w-full text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 ">
                                        {{ User.customer.alamat }}</p>
                                </div>
                            </div>
                            <div class="flex w-full justify-center items-center md:justify-start md:items-start">
                                <button type="button"
                                    class="mt-6 md:mt-0  bg-transparent text-white hover:text-gray-800 border border-gray-200 py-5 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800  w-96 2xl:w-full text-base font-medium leading-4"
                                    @click="funModal">Mulai Pesan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </HomeLayout>

</template>
