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
    jabatan: {
        type: Object,
        default:()=>({})
    },
    posyandus: {
        type: Object,
        default:()=>({})
    },
})
const Form = useForm({
    name:'',
    posyandus_id:'',
    jabatan:'',
    alamat:'',
    username:'',
    email:'',
    password:'',
    no_telpon:'',
})

function submit() {
    Form.post(route('Customer.store'), {
        onError: (err) => {
            console.log(err)
        }
    });
}


</script>

<template>

    <Head title="Customer" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Form Tambah Customer</h2>
        </template>

        <div class="py-4 relative box-content">
            <section class="p-6 bg-gray-100 hover:bg-gradient-to-br hover:from-purple-400 hover:via-blue-400 hover:to-blue-500 transition-all duration-500 hover:shadow-2xl group text-gray-900">
                <form @submit.prevent="submit()" novalidate="" action="" class="container flex flex-col mx-auto space-y-12">
                    <fieldset class="grid grid-cols-3 gap-6 p-6 rounded-md shadow-sm bg-gray-50">
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                            <div class="col-span-full sm:col-span-3">
                                <label for="firstname" class="text-sm">Nama Lengkap</label>
                                <TextInput id="firstname" type="text" placeholder="nama lengkap" v-model="Form.name"  class="w-full text-gray-900"  />
                                <InputError :message="Form.errors.name"/>
                            </div>
                            <div class="col-span-full sm:col-span-3">
                                <label for="no_telpon" class="text-sm">No. Telepon</label>
                                <TextInput id="no_telpon" type="text" v-model="Form.no_telpon" placeholder="No. telpon" class="w-full text-gray-900"  />
                                <InputError :message="Form.errors.no_telpon"/>

                            </div>

                            <div class="col-span-full">
                                <label for="alamat" class="text-sm">Alamat</label>
                                <TextInput id="alamat" type="text" v-model="Form.alamat" placeholder="" class="w-full text-gray-900"  />
                                <InputError :message="Form.errors.alamat"/>

                            </div>
                            <div class="col-span-full sm:col-span-2">
                                <label for="username" class="text-sm">username</label>
                                <TextInput id="username" type="text" v-model="Form.username" placeholder="@username" class="w-full text-gray-900"  />
                                <InputError :message="Form.errors.username"/>

                            </div>
                            <div class="col-span-full sm:col-span-2">
                                <label for="state" class="text-sm">Password</label>
                                <TextInput id="state" type="text" placeholder="" v-model="Form.password" class="w-full text-gray-900"  />
                                <InputError :message="Form.errors.password"/>

                            </div>
                            <div class="col-span-full sm:col-span-2">
                                <label for="email" class="text-sm">Email</label>
                                <TextInput id="email" type="email" placeholder="" v-model="Form.email" class="w-full text-gray-900"  />
                                <InputError :message="Form.errors.email"/>

                            </div>
                        </div>
                        <PrimaryButton type="submit" class="col-span-full text-center">Simpan</PrimaryButton>
                    </fieldset>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
