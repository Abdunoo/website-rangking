<template>
    <header class="bg-white border-b">
        <div class="flex items-center justify-between px-6 py-4">
            <button class="p-2 hover:bg-gray-100 rounded-lg" @click="$emit('toggleSidebar')">
                <Bars3Icon class="w-6 h-6" />
            </button>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">Admin User</span>
                <img src="https://ui-avatars.com/api/?name=Admin+User" alt="User avatar" class="w-8 h-8 rounded-full" />
            </div>
        </div>
    </header>
</template>
<script setup>
import { Bars3Icon } from '@heroicons/vue/24/outline';

defineEmits(['toggleSidebar']);
</script>
