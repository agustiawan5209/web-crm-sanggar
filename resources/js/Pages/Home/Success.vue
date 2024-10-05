<script setup>
import CardEmbedPDF from '@/Components/Card/CardEmbedPDF.vue';
import { usePage, Head, Link } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';
const props = defineProps({
    penyewaan: {
        type: Object,
        default: () => ({})
    }
})
const uploadPercentage = ref(0);

function cetakStruk() {
    axios.get(props.penyewaan.file_url, {
        responseType: 'blob',
        onDownloadProgress: function (progressEvent) {
            uploadPercentage.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        },

    })
        .then((res) => {
            if (res.status == 200) {
                const doc = window.URL.createObjectURL(new Blob([res.data]));
                const a = document.createElement('a');
                a.href = doc;
                a.setAttribute('download', 'struk-' + props.penyewaan.pembayaran.kode_transaksi + '.pdf');
                document.body.appendChild(a);

                a.click();

            }
        })
        .catch(err => console.log(err))
}
</script>
<template>

    <Head title="berhasil!!" />
    <div>
        <!-- component -->
        <div class="bg-gray-100 h-max">
            <div class="bg-white p-6  md:mx-auto">
                <svg viewBox="0 0 24 24" class="text-green-600 w-16 h-16 mx-auto my-6">
                    <path fill="currentColor"
                        d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z">
                    </path>
                </svg>
                <div class="text-center">
                    <h3 class="md:text-2xl text-base text-gray-900 font-semibold text-center">Penyewaan Berhasil!</h3>
                    <p class="text-gray-600 my-2">Terima kasih telah melakukan penyewaan.</p>
                    <p> Semoga Hari anda menyenangkan! </p>
                    <div class="py-10 text-center">
                        <Link :href="route('Customer.Pembayaran.index')" class="px-12 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3">
                            GO BACK
                        </Link>
                    </div>
                </div>
            </div>


        </div>
        <div class="w-full">
            <progress v-if="uploadPercentage > 0" max="100" :value.prop="uploadPercentage"></progress>

            <PrimaryButton @click="cetakStruk()" type="button" class="w-full ml-3 flex justify-center gap-4 !bg-red-500">
                <font-awesome-icon :icon="['fas', 'file-pdf']" class="text-white" /> <span>Cetak
                    Struk
                    Penyewaan</span>
            </PrimaryButton>
            <CardEmbedPDF :pdfUrl="penyewaan.file_url" />
        </div>
    </div>
</template>
