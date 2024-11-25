<template>
  <header
    class="fixed w-full top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#e0e0e0] px-4 lg:px-10 py-3 backdrop-blur-md bg-white/90">
    <!-- Logo Section -->
    <RouterLink to="/" class="flex items-center space-x-4 text-primary">
      <div class="size-8">
        <svg aria-hidden="true" focusable="false" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="#019863"></path>
        </svg>
      </div>
      <h2 class="hidden md:block text-lg font-bold">Websites Rankings</h2>
    </RouterLink>

    <!-- Navigation and User Actions -->
    <div class="flex items-center space-x-4 md:space-x-8">
      <!-- Search Input -->
      <div class="flex-grow max-w-xs mx-4 relative">
        <label class="block">
          <div class="flex items-center rounded-xl bg-gray-100">
            <div class="pl-4 flex items-center">
              <MagnifyingGlassIcon class="w-6 h-6 text-gray-500" />
            </div>
            <input placeholder="Search" class="w-full px-4 py-2 bg-gray-100 rounded-xl focus:outline-none"
              v-model="state.searchQuery" @input="debouncedSearch" />
          </div>
        </label>

        <!-- Search Results -->
        <transition-group name="fade" tag="div" v-if="state.searchResults.length && !isHomePage"
          class="absolute z-10 bg-white border border-gray-200 rounded-lg mt-1 w-full shadow-lg">
          <div v-for="result in state.searchResults" :key="result.id" class="p-2 hover:bg-gray-100 cursor-pointer"
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
            <span class="text-xs">{{ state.credit }}</span>
          </div>
        </button>
      </div>

      <!-- Profile Dropdown -->
      <div class="hidden md:block relative">
        <div class="bg-center bg-no-repeat bg-cover rounded-full w-10 h-10 cursor-pointer" @click.stop="toggleDropdown"
          :style="{ backgroundImage: `url(${userProfileImage})` }"></div>

        <!-- Dropdown menu -->
        <transition :duration="{ enter: 100, leave: 75 }" enter-active-class="transition ease-out duration-100"
          leave-active-class="transition ease-in duration-75">
          <div v-if="state.isDropdownOpen"
            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-20">
            <div class="py-1">
              <RouterLink v-for="menuItem in dropdownMenuItems" :key="menuItem.to" :to="menuItem.to"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
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

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { debounce } from 'lodash-es';
import { 
MagnifyingGlassIcon,
CreditCardIcon,
UserIcon,
KeyIcon 
} from '@heroicons/vue/24/solid';


// Helpers
import apiClient from '@/helpers/axios';

// Props and Emits
const props = defineProps({
  initialCredits: {
    type: Number,
    default: 0
  }
});

const emit = defineEmits(['update-search']);

// State Management
const state = reactive({
  searchQuery: '',
  searchResults: [],
  isDropdownOpen: false,
  credit: props.initialCredits
});

// Router and Route
const route = useRoute();
const router = useRouter();

// Computed Properties
const isHomePage = computed(() => route.path === '/', {
  lazy: true
});

const userProfileImage = computed(() =>
  'https://cdn.usegalileo.ai/stability/770ef02c-67a6-4814-b5eb-30c958e92f7f.png'
);

// Dropdown Menu Items
const dropdownMenuItems = [
  {
    to: '/profile',
    label: 'Profile',
    icon: UserIcon
  },
  {
    to: '/buy-credits',
    label: 'Buy Credits',
    icon: CreditCardIcon
  },
  {
    to: '/change-password',
    label: 'Change Password',
    icon: KeyIcon
  }
];

// Search Functionality
const debouncedSearch = debounce(async () => {
  // Early return for short queries or home page
  if (state.searchQuery.length < 2 || isHomePage.value) {
    state.searchResults = [];
    return;
  }

  try {
    const controller = new AbortController();
    const signal = controller.signal;

    const response = await apiClient.get('/api/websites', {
      params: {
        search: state.searchQuery,
        limit: 5
      },
      signal
    });

    state.searchResults = response.data.data;
  } catch (error) {
    if (error.name !== 'AbortError') {
      console.error(error)
      state.searchResults = [];
    }
  }
}, 300);

// Result Selection
const selectResult = (result) => {
  state.searchResults = [];
  router.push(`/${result.name}`);
};

// Dropdown Toggle
const toggleDropdown = () => {
  state.isDropdownOpen = !state.isDropdownOpen;
};

// Outside Click Handler
const handleClickOutside = (event) => {
  const dropdownElement = document.querySelector('.relative');
  if (dropdownElement && !dropdownElement.contains(event.target)) {
    state.isDropdownOpen = false;
  }
};

// Update Credits
const updateCredits = () => {
  const user = JSON.parse(localStorage.getItem('_usr'));
  state.credit = user ? user.credits : 0;
};

// Watch for Credit Changes
watch(() => props.initialCredits, (newCredits) => {
  state.credit = newCredits;
});

// Lifecycle Hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  updateCredits();
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Add any necessary styles here */
</style>