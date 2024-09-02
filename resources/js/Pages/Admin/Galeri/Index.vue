<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import axios from 'axios';
// Component
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
const props = defineProps({
    image: {
        type: Object,
    },
    produk: {
        type: Object,
    },
    produk_id: String,
    post_store: String,
    product_details: {
        type: Object,
        default: () => ({}),
    },
    product_config: {
        type: Object,
        default: () => ({}),
    },
})
// console.log(props.image)
const modalVar = ref(false);
const FileForm = useForm({
    file: null,
    is_default: false,
    product_detail_name: [],
})
const urlFile = ref(null)
function fileSelected(event) {
    FileForm.file = event.target.files[0];
    urlFile.value = URL.createObjectURL(event.target.files[0])
}


// Update field Is_default
const message = ref(null);
const messageCode = ref(null);
function UpdatedefaultClick(id) {
    axios({
        method: 'get',
        url: route('Galeri.update', { id: id })
    }).then(function (response) {
        console.log(response)
        message.value = response;
    }).catch(function (res) {
        console.log(res)
    })
}

const deleteModalVar = ref(false);
const deleteModalID = ref(null)
function deleteModalShow(id) {
    deleteModalVar.value = true;
    deleteModalID.value = id;
}

// Modal Delete
const deleteForm = useForm({})
function deleteRow() {
    deleteForm.delete(route("Galeri.Delete", { id: deleteModalID.value }), {
        onSuccess: () => {
            deleteModalVar.value = false;
            deleteModalID.value = null;
        },
        onError: error => console.log(error),
    })
}

function back() {
    return window.history.back()
}
const ConfigProduct = ref([])
const ProductDetails = ref([])
function changeConfig(name, event) {
    ProductDetails.value[name] = event.target.value
}

function create() {
    FileForm.post(route('Galeri.' + props.post_store, { produk_id: props.produk.id, product_detail_name: ProductDetails.value }), {
        onError: error => console.log(error),
        onSuccess: () => {
            modalVar.value = false;
            FileForm.reset()
        }
    })
}



function parseJSON(value) {
    const data = new Array(JSON.parse(value));
    // data = ;
    return data
}
</script>
<template>
    <AuthenticatedLayout>

        <Head title="Produk Galeri" />
        <template #header>
            <h2 class="text-3xl font-medium text-gray-700">Halaman Galeri Produk {{ produk.nama }}</h2>
        </template>

        <Modal :show="deleteModalVar">
            <!-- Modal content -->
            <div class="relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
                <button type="button" @click="deleteModalVar = false"
                    class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-toggle="deleteModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <svg class="text-gray-400  w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="mb-4 text-gray-500 ">Anda Yakin Ingin Menghapus?
                </p>
                <div class="flex justify-center items-center space-x-4">
                    <button type="button" @click="deleteModalVar = false"
                        class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10  focus:ring-gray-600">
                        Batal
                    </button>
                    <button type="button" @click="deleteRow()"
                        class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 ">
                        Ya
                    </button>
                </div>
            </div>
        </Modal>
        <!-- Tombol Tambah -->
        <div
            class="w-full px-8 py-4 md:px-4 md:py-2 lg:p-5 bg-white rounded-md mb-2 flex flex-col md:flex-row items-center gap-8">
            <PrimaryButton type="button" @click="modalVar = !modalVar">Tambah</PrimaryButton>
            <PrimaryButton type="button" @click="back()" class="bg-red-500 hover:bg-red-600">Kembali</PrimaryButton>
            <!-- Form Modal Tambah Foto -->
            <Modal :show="modalVar">
                <section class=" bg-gray-900">
                    <div
                        class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen overflow-y-auto lg:py-0">

                        <div
                            class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-white border-gray-700">
                            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                                <h1 class="text-xl font-bold leading-tight tracking-tight  md:text-2xl text-gray-800">
                                    Masukkan Foto Produk
                                </h1>
                                <form @submit.prevent="create()" class="space-y-4" action="#">

                                    <div class="flex items-center justify-center w-full">
                                        <label for="dropzone-file relative overflow-hidden"
                                            class="flex flex-col items-center justify-center overflow-hidden w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50  text-gray-800">
                                            <div v-if="FileForm.file == null"
                                                class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                        class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF
                                                    (MAX. 800x400px)</p>
                                            </div>
                                            <div v-else>
                                                <img v-if="urlFile" class="w-full object-scale-down" :src="urlFile" />
                                            </div>
                                            <input id="dropzone-file" type="file" class="hidden"
                                                @change="fileSelected($event)" />
                                        </label>
                                    </div>
                                    <InputError :message="FileForm.errors.file" />
                                    <div class="flex flex-row items-center justify-center rounded-md shadow-sm w-full"
                                        role="group">
                                        <div class="flex items-center pl-4 border border-gray-200 rounded ">
                                            <input id="bordered-checkbox-1" type="checkbox" value="isdefault"
                                                name="isdefault" v-model="FileForm.is_default"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 ">
                                            <label for="bordered-checkbox-1"
                                                class="w-full py-4 ml-2 pr-3 text-sm font-medium text-gray-900">
                                                Jadikan Gambar Default
                                            </label>
                                        </div>
                                    </div>
                                    <!-- <progress value="50" max="100" class="w-full rounded-md bg-red-500">50%</progress> -->
                                    <div class=" flex flex-col justify-between items-center">
                                        <progress v-if="FileForm.progress" :value="FileForm.progress.percentage"
                                            max="100">
                                            {{ FileForm.progress.percentage }}%
                                        </progress>
                                        <div class="flex justify-around">
                                            <PrimaryButton type="submit">
                                                Simpan
                                            </PrimaryButton>
                                            <PrimaryButton type="button" @click="modalVar = false"
                                                class="bg-red-500 hover:bg-red-600">Batal
                                            </PrimaryButton>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </Modal>
            <p class=" text-sm text-gray-400">Tombol Default Berfungsi Untuk Memilih Foto/Gambar Yang Akan Di Jadikan
                Tampilan Utama Pada Halaman Produk</p>

        </div>
        <table class="max-w-full w-full">
            <tr>
                <th class="px-2 py-2 text-base bg-white ">Gambar</th>
                <th class="px-2 py-2 text-base bg-white ">Default</th>
                <th class="px-2 py-2 text-base bg-white ">Hapus</th>
            </tr>
            <tr v-for="(item, index) in props.image" :key="item" :index="index"
                class="p-3 sm:pb-4 bg-white border-b py-1.5">
                <td class="relative border py-2 flex justify-center">
                    <div class="flex-shrink-0">
                        <img class="w-40 h-40 rounded-3xl border border-gray-200" :src="item.image_url" alt="image photo">
                    </div>
                </td>
                <td class=" border py-2 flex-shrink-0">
                    <label for="green-checkbox" class="ml-2 text-sm font-medium text-gray-900">
                        <span v-if="item.status == 0">Tidak</span>
                        <span v-if="item.status == 1">Ya</span>
                    </label>
                </td>

                <td class=" border py-2 text-base font-semibold text-gray-900">
                    <div class=" flex flex-row items-center justify-center  gap-4 ">
                        <PrimaryButton @click="deleteModalShow(item.id)" type="button"
                            class=" bg-red-600 hover:bg-red-700">
                            Hapus</PrimaryButton>
                        <Link  :href="route('Galeri.edit', { id: item.id })">
                        <PrimaryButton type="button">Defaut</PrimaryButton>
                        </Link>
                    </div>
                </td>
            </tr>
        </table>

    </AuthenticatedLayout>
</template>

<style>
progress {
    border-radius: 7px;
    width: 80%;
    height: 22px;
    box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
}

progress::-webkit-progress-bar {
    background-color: white;
    border-radius: 7px;
}

progress::-webkit-progress-value {
    background-image:
        -webkit-linear-gradient(-45deg,
            transparent 33%, rgba(0, 0, 0, .1) 33%,
            rgba(0, 0, 0, .1) 66%, transparent 66%),
        -webkit-linear-gradient(top,
            rgba(255, 255, 255, .25),
            rgba(0, 0, 0, .25)),
        -webkit-linear-gradient(left, #09c, rgb(211, 176, 176));
    border-radius: 7px;
    box-shadow: 1px 1px 5px 3px rgba(163, 152, 152, 0.8);
}

progress::-moz-progress-bar {
    background-color: white;
    border-radius: 7px;
}

progress::-moz-progress-value {
    background-image:
        -webkit-linear-gradient(-45deg,
            transparent 33%, rgba(0, 0, 0, .1) 33%,
            rgba(0, 0, 0, .1) 66%, transparent 66%),
        -webkit-linear-gradient(top,
            rgba(255, 255, 255, .25),
            rgba(0, 0, 0, .25)),
        -webkit-linear-gradient(left, #09c, rgb(211, 176, 176));
    border-radius: 7px;
    box-shadow: 1px 1px 5px 3px rgba(163, 152, 152, 0.8);
}
</style>
