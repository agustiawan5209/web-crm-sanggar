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
    penyewaan: {
        type: Object,
        default: () => ({})
    }
})

const Form = useForm({
    status: props.penyewaan.status,
    status_bayar: props.penyewaan.pembayaran.status,
    keterangan: '',
    tgl_pengembalian: props.penyewaan.tgl_pengembalian,
})

const updatePembayaranModal = ref(false);
function showPembayaranModal() {
    updatePembayaranModal.value = true;
}

function closePembayaranModal() {
    updatePembayaranModal.value = false;
}

function submitPembayaranUpdate() {
    Form.put(route('Pembayaran.update', { slug: props.penyewaan.pembayaran.id }), {
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

// Penyewaan
const updatePenyewaanModal = ref(false);
function showPenyewaanModal() {
    updatePenyewaanModal.value = true;
}

function closePenyewaanModal() {
    updatePenyewaanModal.value = false;
}

function submitPenyewaanUpdate() {
    Form.put(route('Penyewaan.update', { slug: props.penyewaan.id, }), {
        preserveState: false,
        onError: (err) => {
            console.log(err);
        },
        onSuccess: () => {
            closePenyewaanModal()
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

    <Head title="Penyewaan" />

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
                    <InputError :message="Form.errors.status" />
                </div>
                <div class="relative w-full">
                    <InputLabel class="w-full" value="Keterangan Status Pembayaran" />
                    <TextInput v-model="Form.keterangan" class="w-full" />
                    <InputError :message="Form.errors.keterangan" />

                </div>
                <div class="flex gap-4">

                    <PrimaryButton type="button" class="!bg-red-600 hover:!bg-red-800"
                        @click="updatePembayaranModal = false">Batalkan</PrimaryButton>
                    <PrimaryButton type="submit">Simpan</PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
    <Modal :show="updatePenyewaanModal">
        <div class="bg-white p-4">
            <div class="p-2 mb-3 rounded-md bg-gray-200">
                <h3 class="text-lg tracking-wide font-semibold">Form Update Status Penyewaan Penyewaan</h3>
            </div>
            <form @submit.prevent="submitPenyewaanUpdate()"
                class="w-full max-w-full flex flex-col space-y-4 justify-center">
                <div class="relative w-full">
                    <InputLabel class="w-full" value="Update Status Penyewaan" />
                    <select id="order" v-model="Form.status"
                        class="px-2 py-1 md:px-3 md:py-2 placeholder-gray-400 border focus:outline-none w-full sm:text-sm border-gray-200 shadow-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none ">
                        <option value="">-----</option>
                        <option value="Dalam Penyewaan">Dalam Penyewaan</option>
                        <option value="SELESAI">SELESAI (Di Kembalikan)</option>
                        <option value="Di Batalkan">Di Batalkan</option>
                    </select>
                    <InputError :message="Form.errors.status" />
                </div>

                <div class="col-span-2" v-if="penyewaan.jenis == 'alat'">
                    <label for="tgl_pengembalian" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Pengembalian</label>
                    <input type="date" v-model="Form.tgl_pengembalian" name="tgl_pengembalian" id="tgl_pengembalian"
                        placeholder="Full Name" :required="penyewaan.jenis == 'alat'"
                        class="w-full py-3 px-4 border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500">
                    <InputError :message="Form.errors.tgl_pengembalian" />

                </div>
                <div class="flex gap-4">

                    <PrimaryButton type="button" class="!bg-red-600 hover:!bg-red-800"
                        @click="updatePenyewaanModal = false">Batalkan</PrimaryButton>
                    <PrimaryButton type="submit">Simpan</PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Penyewaan</h2>
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
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.customer.nama }} </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">No. Telpon</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.customer.user.phone }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Alamat</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.customer.alamat }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-span-full  ">
                                <ul class="flex flex-col space-y-20">
                                    <li class="flex gap-3 py-2 border-b">
                                        <span class="text-2xl">Penyewaan</span>
                                    </li>
                                </ul>

                                <table class="w-full table">
                                    <colgroup>
                                        <col class="md:w-36">
                                        <col class="w-3">
                                        <col>
                                    </colgroup>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Nama Produk</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.produk }} </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Jenis</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.jenis }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Tanggal Pengambilan</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.tgl_pengambilan }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Tanggal Pengembalian/Selesai</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.tgl_pengembalian }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm border-b py-2 font-bold capitalize">Status Penyewaan</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <div class="cursor-pointer text-sm flex gap-4">
                                                <span v-if="penyewaan.status == 'Dalam Penyewaan'"
                                                    class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-yellow-700">

                                                    <p class="whitespace-nowrap">{{ penyewaan.status
                                                        }}</p>
                                                </span>
                                                <span v-else-if="penyewaan.status == 'DIBATALKAN'"
                                                    class="inline-flex items-center justify-center rounded-full bg-red-100 px-2.5 py-0.5 text-red-700">

                                                    <p class="whitespace-nowrap">{{ penyewaan.status
                                                        }}</p>
                                                </span>
                                                <span v-else
                                                    class="inline-flex items-center justify-center rounded-full bg-green-100 px-2.5 py-0.5 text-green-700">

                                                    <p class="whitespace-nowrap">{{ penyewaan.status
                                                        }}</p>
                                                </span>
                                                <PrimaryButton @click="showPenyewaanModal()" type="button">Update
                                                    Status
                                                    Penyewaan</PrimaryButton>
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
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Bukti Bayar</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <img :src="penyewaan.pembayaran.bukti_url" alt="Bukti Bayar"
                                                class="w-96 h-96 object-scale-down">
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Jenis Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.pembayaran.jenis_bayar
                                            }} </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Total Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{
                                            penyewaan.pembayaran.total_transaksi }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Sub Total Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.pembayaran.sub_total_transaksi }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Tanggal Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800"> {{ penyewaan.pembayaran.tgl }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold capitalize">Status Pembayaran</td>
                                        <td>:</td>
                                        <td class="text-sm border-b text-gray-800">
                                            <div class="cursor-pointer text-sm flex gap-4">
                                                <span v-if="penyewaan.pembayaran.status == 'PENDING'"
                                                    class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-yellow-700">

                                                    <p class="whitespace-nowrap">{{ penyewaan.pembayaran.status
                                                        }}</p>
                                                </span>
                                                <span v-else-if="penyewaan.pembayaran.status == 'DIBATALKAN'"
                                                    class="inline-flex items-center justify-center rounded-full bg-red-100 px-2.5 py-0.5 text-red-700">

                                                    <p class="whitespace-nowrap">{{ penyewaan.pembayaran.status
                                                        }}</p>
                                                </span>
                                                <span v-else
                                                    class="inline-flex items-center justify-center rounded-full bg-green-100 px-2.5 py-0.5 text-green-700">

                                                    <p class="whitespace-nowrap">{{ penyewaan.pembayaran.status
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
