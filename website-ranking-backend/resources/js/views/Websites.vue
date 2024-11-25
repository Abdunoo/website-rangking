<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Websites</h1>
            <button class="btn btn-primary">Add Website</button>
        </div>

        <div class="card">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Name</th>
                        <th class="text-left py-3">URL</th>
                        <th class="text-left py-3">Category</th>
                        <th class="text-left py-3">Rank</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="website in websites" :key="website.id" class="border-b">
                        <td class="py-3">{{ website.name }}</td>
                        <td class="py-3">{{ website.url }}</td>
                        <td class="py-3">{{ website.category }}</td>
                        <td class="py-3">#{{ website.rank }}</td>
                        <td class="py-3">
                            <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                            <button class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

const websites = ref([
    { id: 1, name: 'Example.com', url: 'https://example.com', category: 'Technology', rank: 1 },
    { id: 2, name: 'Sample.com', url: 'https://sample.com', category: 'Business', rank: 2 },
    // Add more sample data as needed
]);
</script>
