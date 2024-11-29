<template>
  <header
    class="fixed w-full top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-solid border-b-gray-300 px-4 lg:px-10 py-3 backdrop-blur-md bg-white/90">
    <!-- Logo Section -->
    <RouterLink to="/" class="flex items-center space-x-4 text-primary">
      <div class="size-10">
        <img :src="logo" alt="">
      </div>
      <h2 class="hidden md:block text-lg font-bold">Websites Rankings</h2>
    </RouterLink>

    <div class="flex items-center space-x-4 md:space-x-8">
      <div class="relative max-w-xs mx-4 flex-grow">
        <div class="grid grid-cols-2 rounded-xl bg-gray-100 border border-gray-300 text-gray-500">
          <div class="flex items-center">
            <div class="md:pl-2 flex items-center">
              <MagnifyingGlassIcon class="w-4 h-4 " />
            </div>
            <input placeholder="Search" class="w-full px-1 md:px-4 py-2 bg-gray-100 focus:outline-none rounded-l-xl"
              v-model="searchQuery" @input="debouncedSearch" />
          </div>


          <select id="categoryFilter"
            class="px-1 md:px-4 py-2 bg-gray-100 border-l border-gray-300 focus:outline-none focus:ring focus:ring-gray-300 rounded-r-xl"
            v-model="selectedCategory" @change="debouncedSearch">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>

        <transition-group name="fade" tag="div" v-if="searchResults.length"
          class="absolute z-10 bg-white border border-gray-200 rounded-lg mt-1 w-full shadow-lg">
          <div v-for="result in searchResults" :key="result.id" class="p-2 hover:bg-gray-100 cursor-pointer"
            @click="selectResult(result)">
            {{ result.name }}
          </div>
        </transition-group>
      </div>

      <!-- Credits Button -->
      <div class="flex gap-2">
        <button
          class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover-bg-primary">
          <div class="text-white flex flex-col space-x-1 items-center justify-center">
            <CreditCardIcon class="w-5 h-5" />
            <span class="text-xs">{{ dataStore.credits }}</span>
          </div>
        </button>
      </div>

      <!-- Profile Dropdown -->
      <div class="hidden md:block relative">
        <div class="bg-center border bg-no-repeat bg-cover rounded-full w-10 h-10 cursor-pointer"
          @click.stop="toggleDropdown" :style="{ backgroundImage: `url(${dataStore.profilePhoto})` }"></div>
        <transition :duration="{ enter: 100, leave: 75 }">
          <div v-if="isDropdownOpen"
            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-20">
            <div class="py-1">
              <RouterLink v-for="menuItem in dropdownMenuItems" :key="menuItem.to" :to="menuItem.to"
                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                <component :is="menuItem.icon" class="w-5 h-5" />
                {{ menuItem.label }}
              </RouterLink>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script>
import MagnifyingGlassIcon from '@heroicons/vue/24/solid/MagnifyingGlassIcon';
import CreditCardIcon from '@heroicons/vue/24/solid/CreditCardIcon';
import UserIcon from '@heroicons/vue/24/solid/UserIcon';
import KeyIcon from '@heroicons/vue/24/solid/KeyIcon';
import apiClient from '@/helpers/axios';
import { onMounted, onBeforeUnmount, reactive, toRefs } from 'vue';
import router from '@/router';
import { useRoute } from 'vue-router';
import { useDataStore } from '@/store/dataStore.js';
import { debounce } from 'lodash-es';
import logo from '@/assets/images/logo.webp';

export default {
  components: {
    MagnifyingGlassIcon,
    CreditCardIcon,
    UserIcon,
    KeyIcon,
  },
  setup() {
    const state = reactive({
      isDropdownOpen: false,
      searchQuery: '',
      categories: [],
      selectedCategory: '',
      searchResults: [],
      dropdownMenuItems: [
        { to: '/profile', label: 'Profile', icon: UserIcon },
        { to: '/buy-credits', label: 'Buy Credits', icon: CreditCardIcon },
        { to: '/change-password', label: 'Change Password', icon: KeyIcon }
      ]
    });

    const dataStore = useDataStore();
    const route = useRoute();

    const isHomePage = () => {
      return route.path === '/';
    };

    const debouncedSearch = debounce(async () => {
      dataStore.updateSearchQuery(state.searchQuery, state.selectedCategory);
      if (isHomePage()) {
        state.searchResults = [];
        return;
      }
      dataStore.setLoading(true);
      try {
        const response = await apiClient.get('/api/public/websites', {
          params: { search: state.searchQuery, limit: 5, cat: state.selectedCategory }
        });
        state.searchResults = response.data.data;
      } catch (error) {
        console.error(error);
      }
      dataStore.setLoading(false);
    }, 500);

    const getLstCategories = debounce(async () => {
      dataStore.setLoading(true);
      try {
        const response = await apiClient.get('/api/public/categories');
        state.categories = response.data.data;
      } catch (error) {
        console.error(error);
      }
      dataStore.setLoading(false);
    }, 500);

    const selectResult = (result) => {
      state.searchResults = [];
      router.push(`/${result.name}`);
    };

    const toggleDropdown = () => {
      state.isDropdownOpen = !state.isDropdownOpen;
    };

    const handleClickOutside = (event) => {
      const dropdownElement = document.querySelector('.relative');
      if (dropdownElement && !dropdownElement.contains(event.target)) {
        state.isDropdownOpen = false;
      }
    };

    onMounted(() => {
      document.addEventListener('click', handleClickOutside);
      let token = localStorage.getItem('token');
      if (token) {
        dataStore.fetchUserData();
      }

      getLstCategories();

    });

    onBeforeUnmount(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      ...toRefs(state),
      dataStore,
      debouncedSearch,
      toggleDropdown,
      selectResult,
      logo,
      isHomePage
    };
  }
};
</script>