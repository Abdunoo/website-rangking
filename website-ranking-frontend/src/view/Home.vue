<template>
  <div class="hidden md:flex flex-col flex-1 w-full bg-white">
    <h2 class="text-primary text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Rankings</h2>
    <div class="px-4 py-3 @container">
      <div class="flex overflow-hidden rounded-xl border border-[#dce0e5] bg-white shadow-md">
        <table class="min-w-full">
          <thead class="bg-primary text-white">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium leading-normal">Rank</th>
              <th class="px-4 py-3 text-left text-sm font-medium leading-normal">Change</th>
              <th class="px-4 py-3 text-left text-sm font-medium leading-normal">Domain</th>
              <th class="px-4 py-3 text-left text-sm font-medium leading-normal">Website</th>
              <th class="px-4 py-3 text-left text-sm font-medium leading-normal">Category</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="website in websites" :key="website.id"
              class="border-t border-t-[#dce0e5] hover:bg-[#E8F5E9] transition duration-200 cursor-pointer"
              @click="toDetail(website.name)">
              <td class="h-[72px] px-4 py-2 text-[#111418] text-sm font-normal leading-normal">{{ website.rank }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span :class="website.previous_rank > website.rank ? 'text-secondary' : 'text-red-500'">
                  {{ website.previous_rank > website.rank ? '↑' : '↓' }}
                  {{ website.previous_rank - website.rank }}
                </span>
              </td>
              <td class="h-[72px] px-4 py-2 text-secondary text-sm font-normal leading-normal">{{ website.domain }}</td>
              <td class="h-[72px] px-4 py-2 text-[#111418] text-sm font-normal leading-normal">{{ website.name }}</td>
              <td class="h-[72px] px-4 py-2 text-secondary text-sm font-normal leading-normal">{{ website.category }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="errorMessage" class="text-danger text-sm mt-2">{{ errorMessage }}</div>
      <!-- Pagination Buttons -->
      <div class="flex justify-between mt-4">
        <button
          class="px-4 py-2 bg-primary text-white rounded hover:bg-primary hover-bg-primary disabled:bg-gray-200 disabled:cursor-not-allowed"
          :disabled="currentPage === 1" @click="prevPage">
          Previous
        </button>
        <button
          class="px-4 py-2 bg-primary text-white rounded hover:bg-primary hover-bg-primary disabled:bg-gray-200 disabled:cursor-not-allowed"
          :disabled="currentPage === totalPages" @click="nextPage">
          Next
        </button>
      </div>
    </div>
  </div>
  <!-- Mobile cards -->
  <div class="md:hidden divide-y divide-gray-200 w-full space-y-2 pb-10">
    <WebsiteCard v-for="website in websites" :key="website.id" :website="website" />
    <!-- Pagination Buttons -->
    <div class="flex justify-between mt-4">
        <button
          class="px-4 py-2 bg-primary text-white rounded hover:bg-primary hover-bg-primary disabled:bg-gray-200 disabled:cursor-not-allowed"
          :disabled="currentPage === 1" @click="prevPage">
          Previous
        </button>
        <button
          class="px-4 py-2 bg-primary text-white rounded hover:bg-primary hover-bg-primary disabled:bg-gray-200 disabled:cursor-not-allowed"
          :disabled="currentPage === totalPages" @click="nextPage">
          Next
        </button>
      </div>
  </div>

  <button v-show="showScrollTop" @click="scrollToTop"
    class="fixed bottom-5 right-5 bg-primary text-white rounded-full p-4 shadow-lg hover:hover-bg-primary focus:outline-none transition-all">
    <ArrowUpIcon class="size-6 text-white" />
  </button>
</template>

<script>
import apiClient from '@/helpers/axios';
import router from '@/router';
import { onBeforeUnmount, onMounted, reactive, ref, toRefs, watch } from 'vue';
import { ArrowUpIcon } from '@heroicons/vue/24/solid';
import { debounce } from 'lodash-es';
import WebsiteCard from '@/components/WebsiteCard.vue';

export default {
  name: 'Home',
  components: {
    ArrowUpIcon,
    WebsiteCard
  },
  props: {
    searchQuery: {
      type: String,
      default: null,
    },
  },
  setup(props) {
    const state = reactive({
      websites: [],
      currentPage: 1,
      totalPages: 1,
      errorMessage: '',
    });

    const showScrollTop = ref(false);

    // Create a debounced version of getListWebsite
    const debouncedGetListWebsite = debounce(async (page = 1, searchQuery = '') => {
      try {
        const response = await apiClient.get(`api/websites?page=${page}&search=${searchQuery}`);
        state.websites = response.data.data;
        state.totalPages = response.data.last_page || 1;
        state.errorMessage = ''; // Clear error message on success
      } catch (err) {
        console.error("An error occurred:", err);
        state.errorMessage = 'Failed to load data. Please try again later.'; // Set error message
      }
    }, 500); // 500ms delay

    const getListWebsite = (page = 1, searchQuery = '') => {
      debouncedGetListWebsite(page, searchQuery);
    };

    const prevPage = () => {
      if (state.currentPage > 1) {
        state.currentPage--;
        getListWebsite(state.currentPage, props.searchQuery);
      }
    };

    const nextPage = () => {
      if (state.currentPage < state.totalPages) {
        state.currentPage++;
        getListWebsite(state.currentPage, props.searchQuery);
      }
    };

    const toDetail = (name) => {
      router.push(`/${name}`);
    };

    const handleScroll = () => {
      showScrollTop.value = window.scrollY > 200;
    };

    const scrollToTop = () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    watch(
      () => props.searchQuery, // Watched value
      (newQuery) => {
        // When the query changes, fetch filtered websites
        if (newQuery) {
          state.currentPage = 1; // Reset to first page
          getListWebsite(1, newQuery);
        } else {
          // If query is empty, reset to initial state
          getListWebsite();
        }
      },
      { immediate: true } // Run the watcher immediately on component load
    );

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
      getListWebsite();
    });

    onBeforeUnmount(() => {
      window.removeEventListener('scroll', handleScroll);
      // Cancel any pending debounced calls
      debouncedGetListWebsite.cancel();
    });

    return {
      ...toRefs(state),
      getListWebsite,
      prevPage,
      nextPage,
      toDetail,
      showScrollTop,
      scrollToTop,
    };
  },
};
</script>