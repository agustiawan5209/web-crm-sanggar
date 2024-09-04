<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Navbar from '@/Layouts/Navbar.vue';
const showingNavigationDropdown = ref(false);

function getThemeFromLocalStorage() {
    // if user already changed the theme, use it
    if (window.localStorage.getItem('dark')) {
        return JSON.parse(window.localStorage.getItem('dark'))
    }

    // else return their preferences
    return (
        !!window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches
    )
}
function setThemeToLocalStorage(value) {
    window.localStorage.setItem('dark', value)
}

const dark = getThemeFromLocalStorage()
function toggleThemeF() {
    dark = !dark
    setThemeToLocalStorage(dark)
}


const isSideMenuOpen = ref(false)
function toggleSideMenu() {
    isSideMenuOpen.value = !isSideMenuOpen.value
}
function closeSideMenu() {
    isSideMenuOpen.value = false
}
const isNotificationsMenuOpen = ref(false)
function toggleNotificationsMenu() {
    isNotificationsMenuOpen.value = !isNotificationsMenuOpen.value
}
function closeNotificationsMenu() {
    isNotificationsMenuOpen.value = false
}
const isProfileMenuOpen = ref(false)
function toggleProfileMenu() {
    isProfileMenuOpen.value = !isProfileMenuOpen.value
}
function closeProfileMenu() {
    isProfileMenuOpen.value = false
}
const isPagesMenuOpen = ref(false)
function togglePagesMenu() {
    isPagesMenuOpen.value = !isPagesMenuOpen.value
}
// Modal
const isModalOpen = ref(false)
const trapCleanup = ref(null)
function openModal() {
    isModalOpen.value = true
    trapCleanup.value = focusTrap(document.querySelector('#modal'))
}
function closeModal() {
    isModalOpen.value = false
    trapCleanup()
}
</script>

<template>
    <div class="flex h-screen bg-gray-50  overflow-hidden" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- Desktop sidebar -->
        <aside class="z-20 hidden w-[17%] overflow-y-auto  bg-gray-800 md:block flex-shrink-0">
            <div class="py-4">
                <a class="ml-6 text-base font-bold  text-gray-200 text-center" href="#">
                    Sistem Informasi Sanggar Seni Kawali
                </a>
                <Navbar />
            </div>
        </aside>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div v-show="isSideMenuOpen"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
        <aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto  bg-gray-800 md:hidden"
            v-show="isSideMenuOpen" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">
            <div class="py-4  text-gray-400">
                <a class="ml-6 text-lg font-bold text-gray-800 " href="#">
                    Sisfo Klinik
                </a>
                <Navbar/>
            </div>
        </aside>
        <div class="flex flex-col flex-1 w-[72%]">
            <header class="z-10 py-4 px-4 bg-white shadow-md">
                <div
                    class="container flex items-center justify-between h-full mx-auto text-purple-600 ">
                    <!-- Mobile hamburger -->
                    <button class="p-1 pr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                        @click="toggleSideMenu" aria-label="Menu">
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <!-- Search input -->
                    <div class="flex justify-center flex-1 lg:pr-32">
                       <slot name="header" />
                    </div>
                    <ul class="flex items-center flex-shrink-0 space-x-6">

                        <!-- Profile menu -->
                        <li class="relative">
                            <div class="profile flex justify-end items-center bg-gray-700 rounded-lg">
                                <!-- Settings Dropdown -->
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex h-full rounded-lg">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-50 bg-gray-700 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>

                                <!-- Hamburger -->
                                <div class="mr-2 flex items-center sm:hidden">
                                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                            <path :class="{
                                                hidden: showingNavigationDropdown,
                                                'inline-flex': !showingNavigationDropdown,
                                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h16M4 18h16" />
                                            <path :class="{
                                                hidden: !showingNavigationDropdown,
                                                'inline-flex': showingNavigationDropdown,
                                            }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </header>
            <main class="h-screen overflow-y-auto px-3">
                <slot/>
            </main>
        </div>
    </div>
</template>
