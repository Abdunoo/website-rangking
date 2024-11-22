<template>
  <div class="flex flex-col flex-1 w-full">
    <h2 class="text-[#111418] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Rankings</h2>
    <div class="px-4 py-3 @container">
      <div class="flex overflow-hidden rounded-xl border border-[#dce0e5] bg-white">
        <table class="min-w-full">
          <thead>
            <tr class="bg-white">
              <th class="px-4 py-3 text-left text-[#111418] text-sm font-medium leading-normal">Rank</th>
              <th class="px-4 py-3 text-left text-[#111418] text-sm font-medium leading-normal">Domain</th>
              <th class="px-4 py-3 text-left text-[#111418] text-sm font-medium leading-normal">Change</th>
              <th class="px-4 py-3 text-left text-[#111418] text-sm font-medium leading-normal">Website</th>
              <th class="px-4 py-3 text-left text-[#111418] text-sm font-medium leading-normal">Category</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="website in websites" :key="website.id" class="border-t border-t-[#dce0e5] hover:bg-gray-100 transition duration-200" @click="toDetail(website.name)">
              <td class="h-[72px] px-4 py-2 text-[#111418] text-sm font-normal leading-normal">{{ website.rank }}</td>
              <td class="h-[72px] px-4 py-2 text-[#637588] text-sm font-normal leading-normal">{{ website.domain }}</td>
              <td :class="{'text-red-600': website.rank_change < 0, 'text-green-600': website.rank_change >= 0}" class="h-[72px] px-4 py-2 text-sm font-normal leading-normal">
                {{ website.rank_change < 0 ? website.rank_change : `+${website.rank_change}` }}
              </td>
              <td class="h-[72px] px-4 py-2 text-[#111418] text-sm font-normal leading-normal">{{ website.name }}</td>
              <td class="h-[72px] px-4 py-2 text-[#637588] text-sm font-normal leading-normal">{{ website.category }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="errorMessage" class="text-red-500 text-sm mt-2">{{ errorMessage }}</div>
      <!-- Pagination Buttons -->
      <div class="flex justify-between mt-4">
        <button
          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed"
          :disabled="currentPage === 1"
          @click="prevPage"
        >
          Previous
        </button>
        <button
          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed"
          :disabled="currentPage === totalPages"
          @click="nextPage"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '@/helpers/axios';
import router from '@/router';
import { onMounted, reactive, toRefs } from 'vue';

export default {
  name: 'Home',
  setup() {
    const state = reactive({
      websites: [],
      currentPage: 1,
      totalPages: 1,
      errorMessage: '' // State for error message
    });

    const getListWebsite = async (page = 1) => {
      try {
        const response = await apiClient.get(`api/websites?page=${page}`);
        state.websites = response.data.data; 
        state.totalPages = response.data.last_page || 1;
        state.errorMessage = ''; // Clear error message on success
      } catch (err) {
        console.error("An error occurred:", err);
        state.errorMessage = 'Failed to load data. Please try again later.'; // Set error message
      }
    };

    const prevPage = () => {
      if (state.currentPage > 1) {
        state.currentPage--;
        getListWebsite(state.currentPage);
      }
    };

    const nextPage = () => {
      if (state.currentPage < state.totalPages) {
        state.currentPage++;
        getListWebsite(state.currentPage);
      }
    };

    const toDetail = (name) => {
      router.push(`/${name}`);
    };

    onMounted(() => {
      getListWebsite(state.currentPage);
    });

    return {
      ...toRefs(state),
      getListWebsite,
      prevPage,
      nextPage,
      toDetail,
    };
  },
};

</script>