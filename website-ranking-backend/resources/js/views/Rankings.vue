<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Rankings</h1>
            <button @click="isModalVisible = !isModalVisible" class="btn btn-primary">Update Rankings</button>
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
            <button class="btn btn-secondary" @click="prevPage" :disabled="currentPage === 1">
                Previous
            </button>
            <button class="btn btn-secondary" @click="nextPage" :disabled="rankings.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
    <transition name="fade">
        <div v-if="isModalVisible" class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
            <div @click="isModalVisible = !isModalVisible" class="fixed bg-black opacity-70 inset-0 z-0"></div>
            <div
                class="w-full max-w-lg p-3 relative max-h-full flex items-center mx-auto my-auto rounded-xl shadow-lg bg-white">
                <div class="flex flex-col justify-center items-center mb-3 w-full">
                    <div class="text-center p-3 flex-auto justify-center leading-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 flex items-center text-red-600 mx-auto"
                            viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 2a1 1 0 011 1v10a1 1 0 01-2 0V3a1 1 0 011-1zm0 16a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd" />
                            <path
                                d="M12 0a12 12 0 00-12 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0zm0 22a10 10 0 110-20 10 10 0 010 20z" />
                        </svg>
                        <h2 class="text-2xl font-bold py-4">Are you sure?</h2>
                        <p class="text-md text-gray-700 px-8">
                            Do you really want to Update Ranking All Websites?
                        </p>
                        <p class="text-md text-red-700 px-8">This maybe take some time</p>
                    </div>
                    <div class="p-3 mt-2 text-center space-x-4 md:block">
                        <button @click="isModalVisible = !isModalVisible" class="mb-2 md:mb-0 btn btn-secondary">
                            Close
                        </button>
                        <button @click="updateRanking" class="mb-2 md:mb-0 btn btn-primary">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
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
            isModalVisible: false,

        });
        const dataStore = useDataStore();
        const toast = useToast();

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

        const updateRanking = async () => {
            state.isModalVisible = false;
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get(`/api/admin/websites/update-rankings`);
                if (response.code === 200) {
                    getLstRanking();
                }
                toast.success('Ranking updated successfully');
            } catch (error) {
                toast.error('Failed to update ranking. Please try again later.')
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
            updateRanking,
            dataStore,
        };
    },
};
</script>
<style>
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 500ms ease-out;
}
</style>
