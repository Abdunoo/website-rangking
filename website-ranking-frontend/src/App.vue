<script setup>
import { ref, watch } from 'vue';
import Header from './components/Header.vue';
import BottomMenu from './components/BottomMenu.vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue'
import { useDataStore } from './store/dataStore';

const dataStore = useDataStore();
</script>

<template>
  <section class="min-h-screen font-inter">
    <Header />
    <main class="my-16 pb-16 w-full h-full bg-white flex justify-center lg:px-40">
      <router-view />
    </main>
    <BottomMenu />
    <LoadingIndicator v-if="dataStore.loading" />
  </section>
</template>

