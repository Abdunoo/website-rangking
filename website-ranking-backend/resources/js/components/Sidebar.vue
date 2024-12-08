<template>
    <aside :class="[
        'fixed top-0 left-0 h-screen w-64 bg-white border-r transition-transform',
        !isOpen && '-translate-x-full',
    ]">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-emerald-600">Ranking Admin</h1>
        </div>
        <nav class="mt-6">
            <RouterLink v-for="item in menuItems" :key="item.name" :to="item.path" :class="[
                'sidebar-link',
                route.path === item.path ? 'active' : '',
            ]">
                <component :is="item.icon" class="w-5 h-5" />
                {{ item.name }}
            </RouterLink>
            <button @click="logout" class="mt-4 w-full text-left sidebar-link text-danger hover:text-danger hover:bg-gray-200">
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405 1.405A2.002 2.002 0 0118 21H6a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v5m-6 0l-3-3m0 0l3-3m-3 3h12" />
                </svg>
                Logout
            </button>
        </nav>
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import {
    ChartBarIcon,
    GlobeAltIcon,
    TagIcon,
    ChartPieIcon,
    UserGroupIcon,
    CreditCardIcon,
    ChatBubbleLeftIcon,
    Cog6ToothIcon,
} from '@heroicons/vue/24/outline';

defineProps({
    isOpen: Boolean,
});

const route = useRoute();
const router = useRouter();

const menuItems = [
    { name: 'Dashboard', icon: ChartBarIcon, path: '/' },
    { name: 'Websites', icon: GlobeAltIcon, path: '/websites' },
    { name: 'Categories', icon: TagIcon, path: '/categories' },
    { name: 'Rankings', icon: ChartPieIcon, path: '/rankings' },
    { name: 'Users', icon: UserGroupIcon, path: '/users' },
    { name: 'Credits', icon: CreditCardIcon, path: '/credits' },
    { name: 'Reviews', icon: ChatBubbleLeftIcon, path: '/reviews' },
    { name: 'Settings', icon: Cog6ToothIcon, path: '/settings' },
];

// Logout function
const logout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('_usr');
    router.push('/login');
};
</script>
