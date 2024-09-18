<script setup>
import { ref, watch, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Calendar, DatePicker, Popover } from 'v-calendar';
import 'v-calendar/style.css';
import axios from 'axios';

const dates = ref(null);
watch(dates, (value) => {
    const Penyewaan = new Date(value)
    const search = `${Penyewaan.getFullYear()}-${String(Penyewaan.getMonth() + 1).padStart(2, '0')}-${Penyewaan.getDate().toString().padStart(2, '0')}`;
    router.get(route('Customer.Pembayaran.index', { search: search }))
})
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

const user = usePage().props.auth.user;
onMounted(() => {
    axios.get(route('grafik.calendar', { user_id: user.id }))
        .then((res) => {
            if (res.status == 200) {
                const sewa = res.data.data;
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
        .catch(err => {
            console.error('Error :' + err)
        })
})
</script>


<template>
    <div class="w-full h-full box-content" v-if="Loaded">
        <DatePicker v-model.lazy="dates" :attributes="AttributeData" expanded
            :locale="{ id: 'id', firstDayOfWeek: 2, masks: { weekdays: 'WWWW' } }" />
    </div>
</template>
