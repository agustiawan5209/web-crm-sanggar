<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, defineProps } from 'vue';

const props = defineProps({
    jasa: {
        type: Object,
        default: () => ({})
    },
})
const Form = useForm({
    nama: '',
    keterangan: '',
    biaya_transportasi: '',
    harga: '',
    status: '0',
})

function submit() {
    Form.post(route('Produk.Jasa.store'), {
        onError: (err) => {
            console.log(err)
        }
    });
}


</script>

<template>

    <Head title="JASA" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Form Tambah JASA</h2>
        </template>

        <div class="py-4 relative box-content">
            <section class="p-6 bg-gray-100 group-hover:bg-gradient-to-br group-hover:from-purple-400 group-hover:via-blue-400 group-hover:to-blue-500 text-gray-900">
                <form @submit.prevent="submit()" novalidate="" action=""
                    class="container flex flex-col mx-auto space-y-12">
                    <div class="space-y-2 col-span-full lg:col-span-1 group-hover:text-white">
                        <p class="font-medium text-white">Data Informasi PRODUK JASA</p>
                        <p class="text-xs">Tambahkan data PRODUK JASA</p>
                    </div>
                    <fieldset class="grid grid-cols-3 gap-6 p-6 rounded-md shadow-sm bg-gray-50">
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                            <div class="col-span-full ">
                                <label for="nama" class="text-base">Nama Produk</label>
                                <TextInput id="nama" type="text" placeholder="nama Produk" v-model="Form.nama"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.nama" />
                            </div>
                            <div class="col-span-full ">
                                <label for="harga" class="text-base">Harga Sewa</label>
                                <TextInput id="harga" type="number" placeholder="Harga Sewa" v-model="Form.harga"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.harga" />
                            </div>
                            <div class="col-span-full ">
                                <label for="biaya_transportasi" class="text-base">Biaya Transportasi</label>
                                <TextInput id="biaya_transportasi" type="number" placeholder="Biaya Transportasi" v-model="Form.biaya_transportasi"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.biaya_transportasi" />
                            </div>
                            <div class="col-span-full">
                                <label for="status" class="text-base w-full">Status</label>
                                <div class="flex items-center gap-4">
                                    <input id="tersedia" type="radio" value="0" v-model="Form.status" class="text-gray-900" />
                                    <label for="tersedia" class="text-sm w-full">Tersedia</label>
                                </div>
                                <div class="flex items-center gap-4">
                                    <input id="tidak_tersedia" type="radio" value="1" v-model="Form.status" class="text-gray-900" />
                                    <label for="tidak_tersedia" class="text-sm w-full">Tidak Tersedia</label>
                                </div>
                                <InputError :message="Form.errors.status" />
                            </div>
                            <div class="col-span-full">
                                <label for="keterangan" class="text-base w-full mb-2">Keterangan</label>
                                <quill-editor id="keterangan" contentType="html" theme="snow"
                                    v-model:content="Form.keterangan" placeholder="@keterangan"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.keterangan" />

                            </div>
                        </div>
                        <PrimaryButton type="submit" class="col-span-full mt-20 text-center">Simpan</PrimaryButton>
                    </fieldset>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
