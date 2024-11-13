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
    diskon: {
        type: Object,
        default: () => ({})
    },
})
const Form = useForm({
    nama: '',
    keterangan: '',
    jumlah: '',
    jenis: 'Get',
    min_quantity: '',
    min_frequency: '',
})

const TypeDiscount = ref('');



function submit() {
    Form.post(route('Diskon.store'), {
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

        <div class="py-4 relative box-content group">
            <section class="p-6 bg-gray-100 group-hover:bg-gradient-to-br group-hover:from-purple-400 group-hover:via-blue-400 group-hover:to-blue-500 text-gray-900">
                <form @submit.prevent="submit()" novalidate="" action=""
                    class="container flex flex-col mx-auto space-y-12">
                    <div class="space-y-2 col-span-full lg:col-span-1 group-hover:text-white">
                        <p class="font-medium">Data Informasi PRODUK JASA</p>
                        <p class="text-xs">Tambahkan data PRODUK JASA</p>
                    </div>
                    <fieldset class="grid grid-cols-3 gap-6 p-6 rounded-md shadow-sm bg-gray-50">
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                            <div class="col-span-full ">
                                <label for="nama" class="text-base">Nama diskon</label>
                                <TextInput id="nama" type="text" placeholder="nama Diskon" v-model="Form.nama"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.nama" />
                            </div>
                            <div class="col-span-full ">
                                <label for="jumlah" class="text-base">jumlah</label>
                                <TextInput id="jumlah" type="number" placeholder="Jumlah Diskon" v-model="Form.jumlah"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.jumlah" />
                            </div>

                            <div class="col-span-full">
                                <label for="jenis" class="text-base w-full">Jenis</label>
                                <div class="flex items-center gap-4">
                                    <input id="get" type="radio" value="Get" v-model="Form.jenis" class="text-gray-900" />
                                    <label for="get" class="text-sm w-full">Get</label>
                                </div>
                                <div class="flex items-center gap-4">
                                    <input id="Keep" type="radio" value="Keep" v-model="Form.jenis" class="text-gray-900" />
                                    <label for="Keep" class="text-sm w-full">Keep</label>
                                </div>
                                <InputError :message="Form.errors.jenis" />
                            </div>

                            <div class="col-span-full " v-if="Form.jenis == 'Get'">
                                <label for="min_quantity" class="text-base">Jumlah Penyewaan Kostum</label>
                                <TextInput id="min_quantity" type="number" placeholder="Jumlah Penyewaan Kustom" v-model="Form.min_quantity"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.min_quantity" />
                            </div>
                            <div class="col-span-full " v-if="Form.jenis == 'Keep'">
                                <label for="Frekuensi" class="text-base">Paket Lengkap</label>
                                <TextInput id="Frekuensi" type="number" placeholder="Paket Lengkap Dalam Sebulan" v-model="Form.min_frequency"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.min_frequency" />
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
