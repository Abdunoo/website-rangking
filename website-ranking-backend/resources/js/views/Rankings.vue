<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Rankings</h1>
            <button class="btn btn-primary">Update Rankings</button>
        </div>

        <div class="card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-center py-3 px-4 text-gray-500">Rank</th>
                        <th class="text-left py-3 px-4 text-gray-500">Website</th>
                        <th class="text-center py-3 px-4 text-gray-500">Score</th>
                        <th class="text-center py-3 px-4 text-gray-500">Previous Rank</th>
                        <th class="text-center py-3 px-4 text-gray-500">Change</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="ranking in rankings" :key="ranking.id">
                        <td class="py-3 px-4 text-center">{{ ranking.rank }}</td>
                        <td class="py-3 px-4 text-left">{{ ranking.domain }}</td>
                        <td class="py-3 px-4 text-center">{{ ranking.rating }}</td>
                        <td class="py-3 px-4 text-center">{{ ranking.previous_rank }}</td>
                        <td class="py-3 px-4 text-center">
                            <span :class="[
                                'px-2 py-1 rounded text-sm',
                                ranking.previous_rank >= ranking.rank ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]">
                                {{ ranking.previous_rank >= ranking.rank ? '↑ ' : '↓ ' }}
                                {{ Math.abs(ranking.rank - ranking.previous_rank) }}
                            </span>
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
                :disabled="rankings.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';
import { useToast } from 'vue-toastification';
import { useDataStore } from '../store/dataStore';

export default {
    name: 'Websites',
    setup() {
        const state = reactive({
            rankings: [],
            currentPage: 1,
            itemsPerPage: 10,
        });
        const dataStore = useDataStore();

        const getLstRanking = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get(`/api/admin/websites?page=${state.currentPage}&limit=${state.itemsPerPage}`);
                state.rankings = response.data.data;
                console.log(response);
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const nextPage = () => {
            state.currentPage++;
            getLstRanking();
        };

        const prevPage = () => {
            if (state.currentPage > 1) {
                state.currentPage--;
                getLstRanking();
            }
        };

        onMounted(() => {
            getLstRanking();
        });

        return {
            ...toRefs(state),
            nextPage,
            prevPage,
        };
    },
};
</script>
