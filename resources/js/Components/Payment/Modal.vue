<script setup>
import { computed, onMounted, onUnmounted, watch, ref, inject } from 'vue';
import { Link, Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '../InputError.vue';
import Modal from '../Modal.vue';
import CalendarPayment from '../Card/CalendarPayment.vue';
const swal = inject('$swal')
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    produk: {
        type: Object,
        default: () => ({}),
    },
    diskon: {
        type: Number,
        default: 1,
    },
    jenisproduk: {
        type: String,
        default: '',
    },
    quantity: {
        type: Number,
        default: 1,
    },
    subtotal: {
        type: Number,
        default: 1,
    },
    sewa: {
        type: [Array, Object],
        default: () => ({}),
    },
});

var harga = props.subtotal;
const JumlahDp = ref(harga / 2);
const Form = useForm({
    dp: JumlahDp.value,
    jenis_bayar: 'Lunas',
    jenis: props.jenisproduk,
    jumlah_bayar: harga,
    total: harga,
    bukti: '',
    tgl_pembayaran: '',
    tgl_pengambilan: '',
    tgl_pengembalian: '',
    produk: props.produk,
    quantity: props.quantity,
    tgl_penyewaan: '',
    lokasi: '',
    ongkir: '',
    biaya_ongkir: 0,
})

// Jumlah Bayar
const statusBayar = ref(null);

watch(statusBayar, (value) => {

    Form.jenis_bayar = value;
    if (value == 'DP') {
        Form.jumlah_bayar = harga / 2;
    }
    if (value == 'Lunas') {
        Form.jumlah_bayar = harga;
    }
})
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



const modalPay = ref(true);
const payLater = ref(true);
const payNow = ref(false);

const typePayment = ref(0);

const paylaterProduk = () => {
    typePayment.value = 1;
}
const paylaterJasa = () => {
    typePayment.value = 2;
}


const paylaterfalse = () => {
    payLater.value = false;
    modalPay.value = false;
    payNow.value = true;
}
const payLaterTrue = () => {
    payLater.value = true;
    modalPay.value = false;
    payNow.value = false;

    if (props.jenisproduk == 'jasa') {
        paylaterProduk();

    }
    if (props.jenisproduk == 'alat') {
        paylaterProduk();
    }
}

function resetModal() {
    modalPay.value = true;
    payLater.value = true;
    payNow.value = false;

    typePayment.value = 0;
}

// Data Pengiriman
const Ongkir = ref('');

const addOngkir = (value) => {
    Form.ongkir = value;
    Form.biaya_ongkir = props.produk.biaya_ongkir
    Form.total = harga + Number(props.produk.biaya_ongkir)
}
const deleteOngkir = (value) => {
    Form.ongkir = value;
    Form.biaya_ongkir = 0;
    Form.total = harga;

}

// Ongkir Alat
watch(Ongkir, (value) => {
    if (value == 'Ambil Sendiri') {
        deleteOngkir(value)
    }
    if (value == 'Kirim Ke Lokasi') {
        addOngkir(value);
    }
    if (value == 'Luar Soppeng') {
        Form.ongkir = value;
        Form.biaya_ongkir = 500000
        Form.total = harga + 500000
    }
    if (value == 'Sekitaran Soppeng') {
        Form.ongkir = value;
        Form.biaya_ongkir = 300000
        Form.total = harga + 300000
    }
})


//
// Submit Penyewaan
function submit() {
    Form.post(route('Penyewaan.Store'), {
        preserveState: true,
        onError: (err) => {
            console.log(err);
        }
    })
}
// Submit Penyewaan later
function submitLater() {
    Form.post(route('Penyewaan.Store.later'), {
        onSuccess: () => {
            window.location.href = route('Customer.riwayat.penyewaan')
        },
        onError: (err) => {
            console.log(err);
        }
    })
}

const tanggal = ()=>{
    let tgl = new Date();
    tgl.setDate(tgl.getDate()+1);
    return tgl.toISOString().split("T")[0];
};

const HariIni = tanggal();
</script>

<template>
    <Modal :show="show">
        <slot />


        <section v-if="modalPay == true" class="body-font h-max bg-gray-100 text-gray-600 overflow-y-auto">
            <div
                class="container mx-auto flex max-w-full flex-col items-center justify-center rounded-lg bg-white px-5 py-10">
                <!-- QR Code Number Account & Uploadfile -->
                <!-- Step Checkout -->
                <h1 class="text-3xl lg:text-4xl font-semibold leading-7 pb-10 lg:leading-9 text-gray-800">Detail
                    Penyewaan
                </h1>

                <div class="inline-flex gap-4 rounded-md shadow-sm" role="group">
                    <button type="button" @click="payLaterTrue()" v-if="jenisproduk == 'jasa'"
                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-orange-200 border border-gray-200 rounded-md hover:bg-gray-100 hover:text-orange-700 focus:z-10 focus:ring-2 focus:ring-orange-700 focus:text-orange-700">
                        Bayar Nanti
                    </button>
                    <button type="button" @click="paylaterfalse()"
                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-green-200 border border-gray-200 rounded-md hover:bg-gray-100 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-700 focus:text-green-700">
                        Bayar Sekarang
                    </button>
                </div>

            </div>
        </section>

        <!-- Bayar Nanti -->
        <section v-if="modalPay == false && payLater == true"
            class="body-font h-max bg-gray-100 text-gray-600 overflow-y-auto relative">
            <button v-if="modalPay == false" type="button" class="absolute top-10 left-10 text-lg cursor-pointer"
                @click="resetModal()">
                <font-awesome-icon :icon="['fas', 'square-xmark']" class="text-read " />
                kembali
            </button>

            <div class="container mx-auto flex max-w-full flex-wrap justify-center rounded-lg bg-white px-5 py-10">
                <CalendarPayment class="mt-10 mb-2" :sewa="sewa" />
                <!-- QR Code Number Account & Uploadfile -->
                <form @submit.prevent="submitLater" enctype="multipart/form-data" class="w-full flex flex-col">

                    <div class="w-full md:w-2/3 mx-auto p-2">
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-lg font-medium mb-6">isi Informasi Tanggal penyewaan Untuk melanjutkan</h2>
                            <div class="space-y-4">

                                <div class="grid grid-cols-2 gap-3">

                                    <div class="col-span-2" v-show="jenisproduk == 'alat'">
                                        <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                            Pengambilan</label>
                                        <input type="date" v-model="Form.tgl_pengambilan" name="cvv" id="cvv"
                                            placeholder="000" :required="jenisproduk == 'alat'"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.tgl_pengambilan" />

                                    </div>
                                    <div class="col-span-2" v-show="jenisproduk == 'jasa'">
                                        <label for="tgl_penyewaan"
                                            class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                            Penyewaan</label>
                                        <input type="date" v-model="Form.tgl_penyewaan" name="tgl_penyewaan"
                                            id="tgl_penyewaan" placeholder="date"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.tgl_penyewaan" />

                                    </div>
                                    <div class="col-span-2">
                                        <label for="lokasi"
                                            class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                                        <input type="text" v-model="Form.lokasi" name="lokasi" id="lokasi"
                                            placeholder=".............."
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.lokasi" />

                                    </div>
                                    <div class="col-span-2" v-if="props.jenisproduk == 'alat'">
                                        <label for="ongkir"
                                            class="block text-sm font-medium text-gray-700 mb-2">Pengiriman</label>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="AmbilSendiri" type="radio" value="Ambil Sendiri"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="AmbilSendiri"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Ambil
                                                Sendiri</label>
                                        </div>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="kirimkelokasi" type="radio" value="Kirim Ke Lokasi"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="kirimkelokasi"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Kirim Ke
                                                Lokasi: harga = {{ produk.harga_ongkir }}</label>
                                        </div>

                                    </div>
                                    <div class="col-span-2" v-if="props.jenisproduk == 'jasa'">
                                        <label for="ongkir"
                                            class="block text-sm font-medium text-gray-700 mb-2">Pengiriman</label>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="LuarSoppeng" type="radio" value="Luar Soppeng"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="LuarSoppeng"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Luar
                                                Soppeng</label>
                                        </div>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="kirimkelokasi" type="radio" value="Sekitaran Soppeng"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="kirimkelokasi"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Sekitaran
                                                Soppeng</label>
                                        </div>

                                    </div>
                                    <div class="col-span-2">
                                        <div class="flex justify-between items-center w-full">
                                            <p class="text-base text-black font-semibold leading-4">Total</p>
                                            <p class="text-base text-gray-800 font-semibold leading-4"> :{{
                                                formatRupiah(Form.total) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <button type="submit"
                                        class="w-full disabled:bg-red-300 bg-green-500 hover:bg-blue-600 text-white font-medium py-3 rounded-lg focus:outline-none">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </section>
        <!-- Bayar Sekarang -->
        <section v-if="modalPay == false && payNow == true"
            class="body-font h-screen bg-gray-100 text-gray-600 overflow-y-auto relative">
            <button v-if="modalPay == false" type="button" class="absolute top-10 left-10 text-lg cursor-pointer"
                @click="resetModal()">
                <font-awesome-icon :icon="['fas', 'square-xmark']" class="text-read " />
                kembali
            </button>

            <div class="container mx-auto flex max-w-full flex-wrap justify-center rounded-lg bg-white px-5 py-10">
                <CalendarPayment class="mt-10 mb-2" :sewa="sewa" />

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
                            <p class="mt-2 text-center font-semibold text-gray-600">{{ produk.nama }}</p>
                            <p class="mt-1 text-center font-medium text-red-500">{{ harga }}
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
                                <div class="col-span-full" v-if="jenisproduk == 'jasa'">
                                    <label for="jenis_bayar" class="text-base w-full">Jenis Pembayaran</label>
                                    <div class="flex gap-7">
                                        <div class="flex items-center gap-4">
                                            <input id="tersedia" type="radio" value="DP" v-model="statusBayar"
                                                class="text-gray-900" />
                                            <label for="tersedia" class="text-sm w-full">DP</label>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <input id="tidak_tersedia" type="radio" value="Lunas" v-model="statusBayar"
                                                class="text-gray-900" />
                                            <label for="tidak_tersedia" class="text-sm w-full">Lunas</label>
                                        </div>
                                    </div>
                                    <InputError :message="Form.errors.jenis_bayar" />
                                </div>
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
                                            placeholder="MM / YY" :min="HariIni"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.tgl_pembayaran" />

                                    </div>
                                    <div class="col-span-2" v-show="jenisproduk == 'alat'">
                                        <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                            Pengambilan</label>
                                        <input type="date" v-model="Form.tgl_pengambilan" name="cvv" id="cvv"
                                            placeholder="000" :required="jenisproduk == 'alat'"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.tgl_pengambilan" />

                                    </div>
                                    <div class="col-span-2" v-show="jenisproduk == 'jasa'">
                                        <label for="tgl_penyewaan"
                                            class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                            Penyewaan</label>
                                        <input type="date" v-model="Form.tgl_penyewaan" name="tgl_penyewaan"
                                            id="tgl_penyewaan" placeholder="date"
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.tgl_penyewaan" />

                                    </div>
                                    <div class="col-span-2">
                                        <label for="lokasi"
                                            class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                                        <input type="text" v-model="Form.lokasi" name="lokasi" id="lokasi"
                                            placeholder=".............."
                                            class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                                        <InputError :message="Form.errors.lokasi" />

                                    </div>
                                    <div class="col-span-2" v-if="props.jenisproduk == 'alat'">
                                        <label for="ongkir"
                                            class="block text-sm font-medium text-gray-700 mb-2">Pengiriman</label>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="AmbilSendiri" type="radio" value="Ambil Sendiri"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="AmbilSendiri"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Ambil
                                                Sendiri</label>
                                        </div>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="kirimkelokasi" type="radio" value="Kirim Ke Lokasi"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="kirimkelokasi"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Kirim Ke
                                                Lokasi: harga = {{ produk.harga_ongkir }}</label>
                                        </div>

                                    </div>
                                    <div class="col-span-2" v-if="props.jenisproduk == 'jasa'">
                                        <label for="ongkir"
                                            class="block text-sm font-medium text-gray-700 mb-2">Pengiriman</label>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="LuarSoppeng" type="radio" value="Luar Soppeng"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="LuarSoppeng"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Luar
                                                Soppeng</label>
                                        </div>
                                        <div class="flex items-center ps-4 border border-gray-200 rounded">
                                            <input id="kirimkelokasi" type="radio" value="Sekitaran Soppeng"
                                                name="bebanpengiriman" v-model="Ongkir"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="kirimkelokasi"
                                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 ">Sekitaran
                                                Soppeng</label>
                                        </div>

                                    </div>
                                    <div class="col-span-2">
                                        <div class="flex justify-between items-center w-full">
                                            <p class="text-base text-black font-semibold leading-4">Total</p>
                                            <p class="text-base text-gray-800 font-semibold leading-4"> :{{
                                                formatRupiah(Form.total) }}
                                            </p>
                                        </div>
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
</template>
