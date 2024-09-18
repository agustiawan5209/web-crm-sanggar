<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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

const props = defineProps({
    pembayaran: {
        type: Object,
        default: () => ({})
    }
})

const Form = useForm({
    slug: props.pembayaran.id,
    status_bayar: '',
    keterangan: '',
})

const updatePembayaranModal = ref(false);
function showPembayaranModal() {
    updatePembayaranModal.value = true;
}

function closePembayaranModal() {
    updatePembayaranModal.value = false;
}

function submitPembayaranUpdate() {
    Form.put(route('Pembayaran.update'), {
        preserveState: false,
        onError: (err) => {
            console.log(err);
        },
        onSuccess: () => {
            closePembayaranModal()
            swal({
                icon: "info",
                title: 'Berhasil',
                text: page.props.message,
                showConfirmButton: true,
                timer: 2000
            });
        }
    })
}
</script>

<template>

    <Head title="Detail " />

    <Modal :show="updatePembayaranModal">
        <div class="bg-white p-4">
            <div class="p-2 mb-3 rounded-md bg-gray-200">
                <h3 class="text-lg tracking-wide font-semibold">Form Update Status Pembayaran Penyewaan</h3>
            </div>
            <form @submit.prevent="submitPembayaranUpdate()"
                class="w-full max-w-full flex flex-col space-y-4 justify-center">
                <div class="relative w-full">
                    <InputLabel class="w-full" value="Update Status Pembayaran" />
                    <select id="order" v-model="Form.status_bayar"
                        class="px-2 py-1 md:px-3 md:py-2 placeholder-gray-400 border focus:outline-none w-full sm:text-sm border-gray-200 shadow-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none ">
                        <option value="">-----</option>
                        <option value="DITERIMA">DITERIMA</option>
                        <option value="DITOLAK">DITOLAK</option>
                        <option value="SELESAI">SELESAI</option>
                    </select>
                    <InputError :message="Form.errors.status_bayar" />
                </div>
                <div class="relative w-full">
                    <InputLabel class="w-full" value="Keterangan Status Pembayaran" />
                    <TextInput v-model="Form.keterangan" class="w-full" />
                    <InputError :message="Form.errors.keterangan" />

                </div>
                <PrimaryButton type="submit">Simpan</PrimaryButton>
            </form>
        </div>
    </Modal>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pembayaran</h2>
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
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.penyewaan.customer.nama }} </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">No. Telpon</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.penyewaan.customer.user.phone }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Alamat</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.penyewaan.customer.alamat }}
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
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Bukti Bayar</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <img :src="pembayaran.bukti_url" alt="Bukti Bayar"
                                                class="w-96 h-96 object-scale-down">
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Jenis Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.jenis_bayar
                                            }} </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Total Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ pembayaran.total_transaksi }}
                                        </td>
                                    </tr>
                                    <tr class="">
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
                                                <PrimaryButton @click="showPembayaranModal()" type="button">Update
                                                    Status
                                                    Pembayaran</PrimaryButton>
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
    </AuthenticatedLayout>
</template>
