<script setup>
import { ref, watch } from 'vue';
import Header from './components/Header.vue';
import BottomMenu from './components/BottomMenu.vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue'
import { isLoading } from './helpers/axios';

const searchQuery = ref('');
const loading = ref(false)


const handleSearchUpdate = (query) => {
  searchQuery.value = query; // Update searchQuery dari Header
};

watch(isLoading, (newValue) => {
  loading.value = newValue
})

</script>

<template>
  <section class="min-h-screen font-inter">
    <Header @update-search="handleSearchUpdate" />
    <main class="my-16 pb-16 w-full h-full bg-white flex justify-center lg:px-40">
      <router-view :searchQuery="searchQuery" />
    </main>
    <BottomMenu @update-search="handleSearchUpdate" />
    <LoadingIndicator v-if="loading" />
  </section>
</template>

