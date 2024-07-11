<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center pl-3 w-full text-sm font-semibold text-gray-800 transition-colors duration-150 text-white hover:text-gray-100 bg-gradient-to-br from-purple-400 via-blue-400 to-blue-500'
        : 'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 text-white hover:text-gray-100'
);
</script>

<template>
    <Link :href="href" :class="classes" class="relative px-4 py-3">
        <slot />
    </Link>
</template>
