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
                <span :class="['text-sm', website.previous_rank >= website.rank ? 'text-primary' : 'text-danger']">
                  {{ getRankChange(website.rank, website.previous_rank) }}
                </span>
              </td>
              <td class="h-[72px] px-4 py-2 text-secondary text-sm font-normal leading-normal">{{ website.domain }}</td>
              <td class="h-[72px] px-4 py-2 text-[#111418] text-sm font-normal leading-normal">{{ website.name }}</td>
              <td class="h-[72px] px-4 py-2 text-secondary text-sm font-normal leading-normal">{{ website.categories.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="errorMessage" class="text-danger text-sm mt-2">{{ errorMessage }}</div>
      <!-- Pagination Buttons -->
      <PaginationButtons 
        :currentPage="currentPage" 
        :totalPages="totalPages" 
        @prev="prevPage" 
        @next="nextPage" 
      />
    </div>
  </div>

  <!-- Mobile cards -->
  <div class="md:hidden divide-y divide-gray-200 w-full space-y-2 pb-10">
    <WebsiteCard v-for="website in websites" :key="website.id" :website="website" />
    <PaginationButtons 
      :currentPage="currentPage" 
      :totalPages="totalPages" 
      @prev="prevPage" 
      @next="nextPage" 
    />
  </div>

  <button v-show="showScrollTop" @click="scrollToTop" class="fixed bottom-1/4 md:bottom-5 right-5 bg-primary text-white rounded-full p-4 shadow-lg hover:bg-primary focus:outline-none transition-all">
    <ArrowUpIcon class="size-6 text-white" />
  </button>
</template>

<script>
import apiClient from '@/helpers/axios';
import router from '@/router';
import { defineAsyncComponent, onBeforeUnmount, onMounted, reactive, ref, toRefs, watch } from 'vue';
import { ArrowUpIcon } from '@heroicons/vue/24/solid';
import { debounce } from 'lodash-es';
import { useDataStore } from '@/store/dataStore';
const PaginationButtons = defineAsyncComponent(() => import('@/components/ui/PaginationButtons.vue'));
const WebsiteCard = defineAsyncComponent(() => import('@/components/WebsiteCard.vue'));

export default {
  name: 'Home',
  components: {
    ArrowUpIcon,
    WebsiteCard,
    PaginationButtons // Register the new component
  },
  setup(props) {
    const state = reactive({
      websites: [],
      currentPage: 1,
      totalPages: 1,
      errorMessage: '',
    });

    const showScrollTop = ref(false);
    const dataStore = useDataStore();

    const getListWebsite = debounce(async (page = 1, searchQuery = '') => {
      dataStore.setLoading(true);
      try {
        const response = await apiClient.get('/api/public/websites', {
          params: { search: searchQuery, page: page, cat: dataStore.selectedCategory }
        });
        state.websites = response.data.data;
        state.totalPages = response.data.last_page || 1;
        state.errorMessage = '';
        scrollToTop();
      } catch (err) {
        console.error("An error occurred:", err);
        state.errorMessage = 'Failed to load data. Please try again later.';
      }
      dataStore.setLoading(false);
    }, 500);

    const prevPage = () => {
      if (state.currentPage > 1) {
        state.currentPage--;
        getListWebsite(state.currentPage, dataStore.searchQuery);
      }
    };

    const nextPage = () => {
      if (state.currentPage < state.totalPages) {
        state.currentPage++;
        getListWebsite(state.currentPage, dataStore.searchQuery);
      }
    };

    const getRankChange = (current, previous) => {
      const diff = previous - current;
      return diff >= 0 ? `↑${Math.abs(diff)}` : `↓${Math.abs(diff)}`;
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
      () => dataStore.searchQuery,
      (newQuery) => {
        state.currentPage = 1;
        getListWebsite(1, newQuery);
      },
      { immediate: true }
    );

    watch(
      () => dataStore.selectedCategory,
      (selectedCategory) => {
        state.currentPage = 1;
        getListWebsite(1, dataStore.searchQuery);
      },
      { immediate: true }
    );

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
      getListWebsite();
    });

    onBeforeUnmount(() => {
      window.removeEventListener('scroll', handleScroll);
      getListWebsite.cancel();
    });

    return {
      ...toRefs(state),
      getListWebsite,
      prevPage,
      nextPage,
      toDetail,
      showScrollTop,
      scrollToTop,
      getRankChange,
    };
  },
};
</script>