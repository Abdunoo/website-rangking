<script setup>
import { ref } from 'vue';
import Header from './components/Header.vue';

const searchQuery = ref('');

const handleSearchUpdate = (query) => {
  searchQuery.value = query; // Update searchQuery dari Header
};
</script>

<template>
  <section class="min-h-screen font-inter">
    <Header @update-search="handleSearchUpdate" />
    <main class="my-16 pb-16 w-full h-full bg-white flex justify-center lg:px-40">
      <router-view :searchQuery="searchQuery" />
    </main>
  </section>
</template>

