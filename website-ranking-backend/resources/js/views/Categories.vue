<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Categories</h1>
      <button class="btn btn-primary">Add Category</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="category in categories" :key="category.id" class="card">
        <h3 class="text-xl font-semibold">{{ category.name }}</h3>
        <p class="text-gray-600 mt-2">{{ category.websiteCount }} websites</p>
        <div class="mt-4 flex gap-2">
          <button class="btn bg-gray-100 hover:bg-gray-200">Edit</button>
          <button class="btn bg-red-100 text-red-600 hover:bg-red-200">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const categories = ref([
  { id: 1, name: 'Technology', websiteCount: 45 },
  { id: 2, name: 'Business', websiteCount: 32 },
  { id: 3, name: 'Entertainment', websiteCount: 28 },
]);
</script>

