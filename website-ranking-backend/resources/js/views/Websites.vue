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
                        <th class="text-left py-3">Domain</th>
                        <th class="text-left py-3">Category</th>
                        <th class="text-left py-3">Rank</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="website in websites" :key="website.id" class="border-b">
                        <td class="py-3">{{ website.name }}</td>
                        <td class="py-3">{{ website.domain }}</td>
                        <td class="py-3">{{ website.categories.name }}</td>
                        <td class="py-3">#{{ website.rank }}</td>
                        <td class="py-3">
                            <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                            <button class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between">
            <button
                class="btn btn-secondary"
                @click="prevPage"
                :disabled="currentPage === 1">
                Previous
            </button>
            <button
                class="btn btn-secondary"
                @click="nextPage"
                :disabled="websites.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';

export default {
    name: 'Websites',
    setup() {
        const state = reactive({
            websites: [],
            currentPage: 1,
            itemsPerPage: 10,
        });

        const getLstWebsites = async () => {
            try {
                const response = await apiClient.get(`/api/admin/websites`,{
                    params: {
                        page: state.currentPage,
                        limit: state.itemsPerPage
                    }
                });
                state.websites = response.data.data;
            } catch (error) {
                console.error(error);
            }
        };

        const nextPage = () => {
            state.currentPage++;
            getLstWebsites();
        };

        const prevPage = () => {
            if (state.currentPage > 1) {
                state.currentPage--;
                getLstWebsites();
            }
        };

        onMounted(() => {
            getLstWebsites();
        });

        return {
            ...toRefs(state),
            nextPage,
            prevPage,
        };
    },
};
</script>
