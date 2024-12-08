<script setup>
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { RouterView } from 'vue-router';
import Sidebar from './components/Sidebar.vue';
import Header from './components/Header.vue';
import LoadingIndicator from './components/LoadingIndicator.vue';
import { useDataStore } from './store/dataStore';

const isSidebarOpen = ref(true);
const dataStore = useDataStore();
const route = useRoute();

// Function to toggle the sidebar
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

// Watch for route changes to manage sidebar state
watch(
    () => route.path,
    (newPath) => {
        if (newPath === '/login') {
            isSidebarOpen.value = false;
        } else {
            isSidebarOpen.value = true;
        }
    },
    { immediate: true } // Ensures that the sidebar state is set immediately when the component mounts
);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Show Sidebar and Header only if it's not the login page -->
        <div v-if="route.path !== '/login'">
            <Sidebar :is-open="isSidebarOpen" />
            <div :class="['transition-all', isSidebarOpen ? 'ml-64' : 'ml-0']">
                <Header @toggle-sidebar="toggleSidebar" />
                <main class="p-6">
                    <RouterView />
                </main>
            </div>
        </div>

        <!-- Only show the login page without sidebar and header -->
        <div v-else>
            <RouterView />
        </div>

        <LoadingIndicator v-if="dataStore.loading" />
    </div>
</template>
