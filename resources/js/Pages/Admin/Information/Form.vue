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
    title: '',
    description: '',
    image: null,
    video: null,
})

const urlFile = ref(null)
function fileSelected(event) {
    Form.image = event.target.files[0];
    urlFile.value = URL.createObjectURL(event.target.files[0])
}

function submit() {
    Form.post(route('Information.store'), {
        onError: (err) => {
            console.log(err)
        }
    });
}

function uploadVideo(event){
    console.log(event)
    Form.video = event.target.files[0];
}

</script>

<template>

    <Head title="Banner" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Form Video Sanggar Kawali</h2>
        </template>

        <div class="py-4 relative box-content group">
            <section class="p-6 bg-gray-100 group-hover:bg-gradient-to-br group-hover:from-purple-400 group-hover:via-blue-400 group-hover:to-blue-500 text-gray-900">
                <form @submit.prevent="submit()" novalidate="" action=""
                    class="container flex flex-col mx-auto space-y-12" enctype="multipart/form-data">
                    <div class="space-y-2 col-span-full lg:col-span-1 group-hover:text-white">
                        <p class="font-medium">Tambah Data Video Sanggar Kawali</p>
                        <p class="text-xs">Video Sanggar Kawali akan ditapilkan dihalaman utama</p>
                    </div>
                    <fieldset class="grid grid-cols-3 gap-6 p-6 rounded-md shadow-sm bg-gray-50">
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                            <!-- <div class="flex items-center justify-center col-span-full">
                                <label for="dropzone-file"
                                    class="relative flex flex-col items-center justify-center overflow-hidden w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50  text-gray-800">
                                    <div v-if="Form.image == null"
                                        class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-800"><span
                                                class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-800">SVG, PNG, JPG or GIF
                                            (MAX. 800x400px)</p>
                                    </div>
                                    <div v-else>
                                        <img v-if="urlFile" class="w-full object-scale-down" :src="urlFile" />
                                    </div>
                                    <input id="dropzone-file" type="file" class="cursor-pointer opacity-0 absolute top-0 left-0 w-full h-full pointer-events-auto"
                                        @change="fileSelected($event)" />
                                </label>
                            </div> -->
                            <div class="col-span-full ">
                                <label for="title" class="text-base">Video </label>
                                <TextInput type="file" name="video" id="video" accept="video/*" required  @change="uploadVideo($event)"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.title" />
                            </div>
                            <div class="col-span-full ">
                                <label for="title" class="text-base">Judul Informasi</label>
                                <TextInput id="title" type="text" placeholder="Judul Informasi" v-model="Form.title"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.title" />
                            </div>



                            <div class="col-span-full">
                                <label for="description" class="text-base w-full mb-2">Keterangan</label>
                                <div class="bg-white">
                                    <quill-editor id="description" contentType="html" theme="snow"
                                    v-model:content="Form.description" placeholder="@description"
                                    class="w-full text-gray-900" />
                                <InputError :message="Form.errors.description" />
                                </div>

                            </div>
                        </div>
                        <div v-if="Form.progress" class="progress-container">
                            <div class="progress-bar" :style="{ width: Form.progress.percentage + '%' }">
                              {{ Form.progress.percentage }}%
                            </div>
                          </div>
                        <PrimaryButton type="submit" class="col-span-full mt-20 text-center">Simpan</PrimaryButton>
                    </fieldset>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
<style >
/* Container untuk progress */
.progress-container {
  width: 100%;
  background-color: #f3f3f3;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin: 10px 0;
}

/* Progress bar */
.progress-bar {
  height: 25px;
  background-color: #4caf50; /* Warna hijau */
  border-radius: 20px;
  color: white;
  text-align: center;
  line-height: 25px;
  font-weight: bold;
  transition: width 0.4s ease;
}

/* Tambahkan animasi */
@keyframes progress-animation {
  from { width: 0; }
  to { width: 100%; }
}

/* Warna berdasarkan persentase */
.progress-bar[style*='width: 50%'] {
  background-color: #ff9800; /* Warna oranye ketika 50% */
}

.progress-bar[style*='width: 75%'] {
  background-color: #2196f3; /* Warna biru ketika 75% */
}

.progress-bar[style*='width: 100%'] {
  background-color: #8bc34a; /* Warna hijau terang ketika 100% */
}
</style>
