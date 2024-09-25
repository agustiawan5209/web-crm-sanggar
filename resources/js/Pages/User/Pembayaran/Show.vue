<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';

import { ref, defineProps, watch, onMounted, inject } from 'vue';

const swal = inject('$swal');
const page = usePage();
onMounted(() => {
    if (page.props.message !== null) {
        swal({
            icon: "success",
            title: 'Berhasil',
            text: page.props.message,
            showConfirmButton: true,
            timer: 2000
        });
    }
})


const props = defineProps({
    pembayaran: {
        type: Object,
        default: () => ({})
    }
})

var harga = props.pembayaran.total;
const JumlahDp = ref(harga / 2);
const Form = useForm({
    slug: props.pembayaran.penyewaan.id,
    jenis_bayar: 'Lunas',
    jenis: props.jenisproduk,
    jumlah_bayar: harga,
    bukti: '',
})
const showModal = ref(false);

// Data bukti Bayar || Gambar
const imageUrl = ref(null);
function swalShow(icon, message) {
    swal({
        icon: icon,
        title: 'Perhatian!!',
        text: message,
        showConfirmButton: true,
        timer: 2000
    });
}
function onFileChange(event) {
    const file = event.target.files[0];
    Form.bukti = file;
    if (!file) {
        imageUrl.value = null;
        return;
    }

    if (!file.type.startsWith('image/')) {
        swalShow('error', 'Pilih Format Gambar Yang Valid')
        imageUrl.value = null;
        return;
    }

    const maxSizeInMB = 2; // Ukuran maksimal dalam MB
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

    if (file.size > maxSizeInBytes) {
        swalShow('error', `FFile Tidak Boleh Lebih Dari ${maxSizeInMB} MB.`)
        imageUrl.value = null;
        return;
    }

    imageUrl.value = URL.createObjectURL(file);
}

//
// Submit Penyewaan
function submit() {
    Form.post(route('Customer.penyewaan.store.pay.later'), {
        preserveState: true,
        onFinish: () => {
            showModal.value = false;
            Form.reset();
        },
        onError: (err) => {
            var txt = "<ul>"
            Object.keys(err).forEach((item, val) => {
                txt += `<li>${err[item]}</li>`
            });
            txt += "</ul>";
            console.log(txt)
            swal({
                title: "Peringatan",
                icon: "error",
                html: txt,
                showCloseButton: true,
                showCancelButton: true,
            });
        }
    })
}
</script>

<template>

    <Head title="Detail" />
    <Modal :show="showModal">

        <section class="body-font h-screen bg-gray-100 text-gray-600 overflow-y-auto">
            <div class="container mx-auto flex max-w-full flex-wrap justify-center rounded-lg bg-white px-5 py-10">
                <!-- QR Code Number Account & Uploadfile -->
                <!-- Step Checkout -->
                <div class="mt-8 max-w-sm md:mb-10 md:ml-10 ">
                    <div class="relative flex pb-12">
                        <div class="absolute inset-0 flex h-full w-10 items-center justify-center">
                            <div class="pointer-events-none h-full w-1 bg-gray-200"></div>
                        </div>
                        <div
                            class="relative z-10 inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <div class="flex-grow pl-4">
                            <h2 class="title-font mb-1 text-sm font-medium tracking-wider text-gray-900">
                                STEP 1</h2>
                            <p class="font-laonoto leading-relaxed">
                                Pilih Jenis Pembayaran
                            </p>
                        </div>
                    </div>
                    <div class="relative flex pb-12">
                        <div class="absolute inset-0 flex h-full w-10 items-center justify-center">
                            <div class="pointer-events-none h-full w-1 bg-gray-200"></div>
                        </div>
                        <div
                            class="relative z-10 inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                            </svg>
                        </div>
                        <div class="flex-grow pl-4">
                            <h2 class="title-font mb-1 text-sm font-medium tracking-wider text-gray-900">
                                STEP 2</h2>
                            <p class="font-laonoto leading-relaxed">
                                Upload Bukti Bayar
                            </p>
                        </div>
                    </div>
                    <div class="relative flex">
                        <div
                            class="relative z-10 inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" class="h-5 w-5" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="3"></circle>
                                <path d="M12 22V8M5 12H2a10 10 0 0020 0h-3"></path>
                            </svg>
                        </div>
                        <div class="flex-grow pl-4">
                            <h2 class="title-font mb-1 text-sm font-medium tracking-wider text-gray-900">
                                STEP 3</h2>
                            <p class="font-laonoto leading-relaxed">
                                Tunggu Status Konfirmasi <span>
                                    <b>Pembayaran</b></span>.
                            </p>
                        </div>
                    </div>
                </div>
                <form @submit.prevent="submit" enctype="multipart/form-data" class="w-full flex flex-col">
                    <div class="mx-auto">
                        <img v-if="imageUrl" class="mx-auto mt-12 h-52 w-52 rounded-lg border p-2 md:mt-0"
                            :src="imageUrl" alt="bukti bayar" />
                        <div>
                            <h1 class="font-laonoto mt-4 text-center text-xl font-bold">Form Pembayaran
                            </h1>
                            <p class="mt-2 text-center font-semibold text-gray-600">{{
                                pembayaran.penyewaan.produk_id.nama }}</p>
                            <p class="mt-1 text-center font-medium text-red-500">{{
                                pembayaran.penyewaan.produk_id.rupiah }}
                            </p>
                        </div>
                        <!-- component -->
                        <div class="mx-auto w-52">
                            <div class="m-4">
                                <div class="flex w-full items-center justify-center">
                                    <label
                                        class="flex h-14 w-full cursor-pointer flex-col border-4 border-dashed border-gray-200 hover:border-gray-300 hover:bg-gray-100">
                                        <div class="mt-4 flex items-center justify-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                            </svg>

                                            <p
                                                class="font-laonoto text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                                Upload Bukti Bayar</p>
                                        </div>
                                        <input type="file" @change="onFileChange" class="opacity-0" accept="image/*" />
                                    </label>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="w-full md:w-2/3 mx-auto p-2">
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-lg font-medium mb-6">Informasi Penyewaan</h2>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">

                                    <div class="col-span-2">
                                        <label for="jumlah_bayar"
                                            class="block text-sm font-medium text-gray-700 mb-2">Jumlah
                                            Pembayaran</label>
                                        <input type="number" :readonly="true" v-model="Form.jumlah_bayar"
                                            name="jumlah_bayar" id="jumlah_bayar" placeholder="Rp. 0000"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.jumlah_bayar" />

                                    </div>
                                    <div class="col-span-2">
                                        <label for="tgl_pembayaran"
                                            class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                            Pembayaran</label>
                                        <input type="date" v-model="Form.tgl_pembayaran" id="tgl_pembayaran"
                                            placeholder="MM / YY"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.tgl_pembayaran" />

                                    </div>
                                </div>
                                <div class="mt-8">
                                    <button type="submit" :disabled="Form.jumlah_bayar == ''"
                                        class="w-full disabled:bg-red-300 bg-green-500 hover:bg-blue-600 text-white font-medium py-3 rounded-lg focus:outline-none">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </section>
    </Modal>
    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pembayaran Sewa</h2>
        </template>

        <div class="md:py-4 relative box-content">
            <section class=" py-2 px-0 md:px-6  md:py-6 bg-gray-100 text-gray-900">
                <form novalidate="" action="" class="container flex flex-col mx-auto space-y-12">
                    <fieldset class="grid grid-cols-3 gap-6 p-6 rounded-md shadow-sm bg-gray-50">
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                            <div class="col-span-full  ">
                                <ul class="flex flex-col space-y-20">
                                    <li class="flex gap-3 py-2 border-b">
                                        <span class="text-2xl">Customer</span>
                                    </li>
                                </ul>

                                <table class="w-full table">
                                    <colgroup>
                                        <col class="md:w-36">
                                        <col class="w-3">
                                        <col>
                                    </colgroup>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Nama Customer</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{
                                            pembayaran.penyewaan.customer.nama }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">No. Telpon</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{
                                            pembayaran.penyewaan.customer.user.phone
                                            }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Alamat</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{
                                            pembayaran.penyewaan.customer.alamat }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Nama Produk</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.penyewaan.produk }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Jenis</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.penyewaan.jenis }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Tanggal Pengambilan</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{
                                            pembayaran.penyewaan.tgl_pengambilan }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Tanggal
                                            Pengembalian/Selesai</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{
                                            pembayaran.penyewaan.tgl_pengembalian }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm border-b py-2 font-bold capitalize">Status Penyewaan</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <div class="cursor-pointer text-sm flex gap-4">
                                                <span v-if="pembayaran.penyewaan.status == 'Dalam Penyewaan'"
                                                    class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-yellow-700">

                                                    <p class="whitespace-nowrap">{{ pembayaran.penyewaan.status
                                                        }}</p>
                                                </span>
                                                <span v-else-if="pembayaran.penyewaan.status == 'DIBATALKAN'"
                                                    class="inline-flex items-center justify-center rounded-full bg-red-100 px-2.5 py-0.5 text-red-700">

                                                    <p class="whitespace-nowrap">{{ pembayaran.penyewaan.status
                                                        }}</p>
                                                </span>
                                                <span v-else
                                                    class="inline-flex items-center justify-center rounded-full bg-green-100 px-2.5 py-0.5 text-green-700">

                                                    <p class="whitespace-nowrap">{{ pembayaran.penyewaan.status
                                                        }}</p>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-span-full  ">
                                <ul class="flex flex-col space-y-20">
                                    <li class="flex gap-3 py-2 border-b">
                                        <span class="text-2xl">Pembayaran</span>
                                    </li>
                                </ul>

                                <table class="w-full table">
                                    <colgroup>
                                        <col class="md:w-36">
                                        <col class="w-3">
                                        <col>
                                    </colgroup>
                                    <tr class="" v-if="pembayaran.bukti_bayar != null">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Bukti Bayar</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <img :src="pembayaran.bukti_url" alt="Bukti Bayar"
                                                class="w-96 h-96 object-scale-down">
                                        </td>
                                    </tr>
                                    <tr class="" v-if="pembayaran.jenis_bayar != null">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Jenis Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.jenis_bayar
                                            }} <PrimaryButton v-if="pembayaran.jenis_bayar =='Bayar Nanti'" @click="showModal = true" type="button">Buat Pembayaran
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Total Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.total_transaksi }}
                                        </td>
                                    </tr>
                                    <tr class="" v-if="pembayaran.tgl != null">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Tanggal Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.tgl }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Status Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <div class="cursor-pointer text-sm flex gap-4">
                                                <span v-if="pembayaran.status == 'PENDING'"
                                                    class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-yellow-700">

                                                    <p class="whitespace-nowrap">{{ pembayaran.status
                                                        }}</p>
                                                </span>
                                                <span v-else-if="pembayaran.status == 'DIBATALKAN'"
                                                    class="inline-flex items-center justify-center rounded-full bg-red-100 px-2.5 py-0.5 text-red-700">

                                                    <p class="whitespace-nowrap">{{ pembayaran.status
                                                        }}</p>
                                                </span>
                                                <span v-else
                                                    class="inline-flex items-center justify-center rounded-full bg-green-100 px-2.5 py-0.5 text-green-700">

                                                    <p class="whitespace-nowrap">{{ pembayaran.status
                                                        }}</p>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>
    </UserLayout>
</template>
