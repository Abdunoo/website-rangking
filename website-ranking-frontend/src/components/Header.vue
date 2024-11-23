<template>
  <header
    class="fixed w-full top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#e0e0e0] px-4 lg:px-10 py-3 backdrop-blur-md bg-white/90">
    <!-- Logo Section -->
    <RouterLink to="/" class="flex items-center space-x-4 text-primary">
      <div class="size-8">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="#019863"></path>
        </svg>
      </div>
      <h2 class="hidden md:block text-lg font-bold">Websites Rankings</h2>
    </RouterLink>

    <!-- Navigation and User Actions -->
    <div class="flex items-center space-x-4 md:space-x-8">
      <!-- Explore Link
      <RouterLink to="/" class="hidden md:block text-primary hover:underline font-medium">
        Explore
      </RouterLink> -->

      <!-- Search Input -->
      <div class="flex-grow max-w-xs mx-4">
        <label class="block">
          <div class="flex items-center rounded-xl bg-[#f0f2f4]">
            <div class="pl-4 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                <path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
              </svg>
            </div>
            <input 
              placeholder="Search"
              class="w-full px-4 py-2 bg-[#f0f2f4] rounded-xl focus:outline-none"
              v-model="searchQuery" 
              @input="updateSearchQuery" 
            />
          </div>
        </label>
      </div>

      <!-- Credits Button -->
      <div class="flex gap-2">
        <button
          class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 hover-bg-primary">
          <div class="text-white flex flex-col space-x-1 items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
              viewBox="0 0 256 256">
              <path
                d="M207.58,63.84C186.85,53.48,159.33,48,128,48S69.15,53.48,48.42,63.84,16,88.78,16,104v48c0,15.22,11.82,29.85,32.42,40.16S96.67,208,128,208s58.85-5.48,79.58-15.84S240,167.22,240,152V104C240,88.78,228.18,74.15,207.58,63.84ZM128,64c62.64,0,96,23.23,96,40s-33.36,40-96,40-96-23.23-96-40S65.36,64,128,64Zm-8,95.86v32c-19-.62-35-3.42-48-7.49V153.05A203.43,203.43,0,0,0,120,159.86Zm16,0a203.43,203.43,0,0,0,48-6.81v31.31c-13, 4.07-29,6.87-48,7.49ZM32,152V133.53a82.88,82.88,0,0,0,16.42,10.63c2.43,1.21,5,2.35,7.58,3.43V178C40.17,170.16,32,160.29,32,152Zm168,26V147.59c2.61-1.08,5.15-2.22,7.58-3.43A82.88,82.88,0,0,0,224,133.53V152C224,160.29,215.83,170.16,200,178Z">
              </path>
            </svg>
            <span class="text-xs">500</span>
          </div>
        </button>
      </div>

      <!-- Profile Dropdown -->
      <div class="hidden md:block relative" @click.stop="toggleDropdown">
        <div 
          class="bg-center bg-no-repeat bg-cover rounded-full w-10 h-10 cursor-pointer"
          style='background-image: url("https://cdn.usegalileo.ai/stability/770ef02c-67a6-4814-b5eb-30c958e92f7f.png");'>
        </div>

        <!-- Dropdown menu -->
        <transition 
          enter-active-class="transition ease-out duration-100"
          enter-from-class="transform opacity-0 scale-95" 
          enter-to-class="transform opacity-100 scale-100"
          leave-active-class="transition ease-in duration-75" 
          leave-from-class="transform opacity-100 scale-100"
          leave-to-class="transform opacity-0 scale-95"
        >
          <div v-if="isDropdownOpen"
            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-20">
            <div class="py-1">
              <RouterLink to="/profile"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Profile
              </RouterLink>

              <RouterLink to="/buy-credits"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12" y2="16"></line>
                </svg>
                Buy Credits
              </RouterLink>

              <RouterLink to="/change-password"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path
                    d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                  </path>
                </svg>
                Change Password
              </RouterLink>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
  name: 'Header',
  emits: ['update-search'],
  setup(props, { emit }) {
    const searchQuery = ref('');
    const isDropdownOpen = ref(false);

    const updateSearchQuery = () => {
      emit('update-search', searchQuery.value);
    };

    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value;
    };

    const handleClickOutside = (event) => {
      if (!event.target.closest('.relative')) {
        isDropdownOpen.value = false;
      }
    };

    onMounted(() => {
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      searchQuery,
      updateSearchQuery,
      isDropdownOpen,
      toggleDropdown,
    };
  },
};
</script>