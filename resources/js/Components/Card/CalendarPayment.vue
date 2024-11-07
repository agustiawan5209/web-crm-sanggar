<script setup>
import { ref, watch, onMounted, defineProps } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Calendar, DatePicker, Popover } from 'v-calendar';
import 'v-calendar/style.css';
import axios from 'axios';


const props = defineProps(['sewa'])
console.log(props.sewa)
const dates = ref(null);
const Loaded = ref(false)
const AttributeData = ref([{
    key: 'today',
    highlight: {
        color: 'purple',
        fillMode: 'solid',
        contentClass: 'italic',
    },
    dates: new Date(),
},])

onMounted(() => {
    if (Loaded.value == false) {
        const sewa = props.sewa.data;
        const tanggal = props.sewa.tanggal;
        for (let i = 0; i < sewa.length; i++) {
            const element = sewa[i];
            // Periksa format tanggal, jika perlu ubah ke format Date
            const date = new Date(element.tanggal); // Contoh

            AttributeData.value.push({
                key: i.toString(),
                highlight: {
                    color: 'blue',
                    fillMode: 'outline',
                    contentClass: 'italic',
                },
                dot: 'red',
                dates: date,
                popover: {
                    label: element.deskripsi,
                    isInteractive: false,
                }
            })
        }
        Loaded.value = true;
    } else {
        console.log('Error Data Gagal Didapat')
    }
})
</script>


<template>
    <div class="w-full h-max box-content" v-if="Loaded">
        <h1 class="bg-white p-2 text-gray-800">Kalendar Penyewaan</h1>
        <p class="text-sm text-justify">keterangan : Jika pada tanggal terdapat titik berwarna merah maka penyewaan untuk produk ini sudah ada. dan jika kosong maka penyewaan unuk produk masing kosong untuk tanggal tersebut</p>
        <DatePicker v-model.lazy="dates" :attributes="AttributeData" expanded
            :locale="{ id: 'id', firstDayOfWeek: 2, masks: { weekdays: 'WWWW' } }" />
    </div>
</template>
