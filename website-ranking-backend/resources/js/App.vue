<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import Sidebar from './components/Sidebar.vue';
import Header from './components/Header.vue';
import LoadingIndicator from './components/LoadingIndicator.vue';
import { useDataStore } from './store/dataStore';

const isSidebarOpen = ref(true);
const dataStore = useDataStore();

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Sidebar :is-open="isSidebarOpen" />
    <div :class="['transition-all', isSidebarOpen ? 'ml-64' : 'ml-0']">
      <Header @toggle-sidebar="toggleSidebar" />
      <main class="p-6">
        <RouterView />
      </main>
      <LoadingIndicator v-if="dataStore.loading" />
    </div>
  </div>
</template>
