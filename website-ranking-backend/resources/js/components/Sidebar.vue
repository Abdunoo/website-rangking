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
        </nav>
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
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
</script>
