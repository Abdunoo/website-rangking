<template>
  <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
    <!-- Ranking and Position Change -->
    <div class="flex flex-wrap justify-between gap-3 p-4">
      <div class="flex flex-col">
        <p class="text-[#111418] tracking-light text-[24px] font-bold leading-tight">{{ website.name }}</p>
        <div class="flex gap-4 mt-2">
          <p class="text-[#637588] tracking-light text-[16px] font-medium">Ranking: {{ website.rank }}</p>
          <p :class="[
            'tracking-light text-[16px] font-medium',
            website.rank_change > 0 ? 'text-green-600' : 'text-red-600'
          ]">
            Position Change: {{ website.rank_change > 0 ? '+' : '' }}{{ website.rank_change }}
          </p>
        </div>
      </div>
    </div>

    <!-- Domain and Category -->
    <div class="flex justify-between px-4 py-2">
      <p class="text-[#637588] text-sm font-normal leading-normal underline">{{ website.domain }}</p>
      <p class="text-[#111418] text-sm font-medium">Category: {{ website.category }}</p>
    </div>

    <!-- Contact Information -->
    <h3 class="text-[#111418] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Contact Information
    </h3>

    <!-- Masked Contact -->
    <div class="flex items-center gap-4 bg-white px-4 min-h-[72px] py-2 justify-between">
      <div class="flex flex-col justify-center">
        <p class="text-[#111418] text-base font-medium leading-normal line-clamp-1">Contact Hidden</p>
        <p class="text-[#637588] text-sm font-normal leading-normal line-clamp-2">Unlock with 10 credits</p>
      </div>
      <div class="shrink-0">
        <button
          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#1980e6] text-white text-sm font-medium leading-normal">
          Unlock Contact
        </button>
      </div>
    </div>

    <!-- Report Contact Changes -->
    <h3 class="text-[#111418] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Report Contact Changes
    </h3>
    <div class="space-y-4 px-4">
      <input placeholder="New Email" class="w-full p-3 border rounded-xl bg-[#f0f2f4] focus:outline-none" />
      <input placeholder="New Phone Number" class="w-full p-3 border rounded-xl bg-[#f0f2f4] focus:outline-none" />
      <textarea placeholder="Additional Notes"
        class="w-full p-3 border rounded-xl bg-[#f0f2f4] focus:outline-none min-h-[100px]"></textarea>
      <button class="w-full bg-[#1980e6] text-white p-3 rounded-xl hover:bg-blue-700">
        Submit Report
      </button>
    </div>

    <!-- Ratings and Reviews -->
    <h3 class="text-[#111418] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Ratings & Reviews</h3>

    <!-- Star Rating -->
    <div class="flex items-center gap-2 px-4 py-2">
      <div class="flex">
        <span class="text-yellow-400 text-2xl">★</span>
        <span class="text-yellow-400 text-2xl">★</span>
        <span class="text-yellow-400 text-2xl">★</span>
        <span class="text-yellow-400 text-2xl">★</span>
        <span class="text-gray-300 text-2xl">★</span>
      </div>
      <span class="text-[#637588] ml-2">(4.2/5 from 256 reviews)</span>
    </div>

    <!-- Write a Review -->
    <div class="px-4 py-4">
      <textarea placeholder="Write your review about Notion..."
        class="w-full p-3 border rounded-xl bg-[#f0f2f4] focus:outline-none min-h-[100px]"></textarea>
      <div class="flex justify-end mt-2">
        <button class="bg-[#1980e6] text-white px-4 py-2 rounded-xl hover:bg-blue-700">
          Submit Review
        </button>
      </div>
    </div>

    <!-- User Reviews -->
    <h3 class="text-[#111418] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">User Reviews</h3>

    <!-- Review Example 1 -->
    <div class="flex w-full flex-row items-start justify-start gap-3 p-4">
      <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
        style='background-image: url("https://cdn.usegalileo.ai/stability/75bf10f7-105c-4005-ab7b-064ef73a5dc6.png");'>
      </div>
      <div class="flex h-full flex-1 flex-col items-start justify-start">
        <div class="flex w-full flex-row items-start justify-start gap-x-3">
          <p class="text-[#111418] text-sm font-bold leading-normal tracking-[0.015em]">Sophie</p>
          <p class="text-[#637588] text-sm font-normal leading-normal">3 days ago</p>
        </div>
        <p class="text-[#111418] text-sm font-normal leading-normal">
          I absolutely love Notion! I use it for personal notes and as a task manager at work.
        </p>
        <div class="flex w-full flex-row items-center justify-start gap-9 pt-2">
          <div class="flex items-center gap-2">
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-gray-300">★</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Review Example 2 -->
    <div class="flex w-full flex-row items-start justify-start gap-3 p-4">
      <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
        style='background-image: url("https://cdn.usegalileo.ai/stability/893b298b-4697-456a-b8bc-42494eb4cf73.png");'>
      </div>
      <div class="flex h-full flex-1 flex-col items-start justify-start">
        <div class="flex w-full flex-row items-start justify-start gap-x-3">
          <p class="text-[#111418] text-sm font-bold leading-normal tracking-[0.015em]">John</p>
          <p class="text-[#637588] text-sm font-normal leading-normal">3 days ago</p>
        </div>
        <p class="text-[#111418] text-sm font-normal leading-normal"> Notion is very useful for organizing information.
          The ability to create custom databases is very powerful.
        </p>
        <div class="flex w-full flex-row items-center justify-start gap-9 pt-2">
          <div class="flex items-center gap-2">
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-gray-300">★</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Review Example 3 -->
    <div class="flex w-full flex-row items-start justify-start gap-3 p-4">
      <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
        style='background-image: url("https://cdn.usegalileo.ai/stability/f02b9009-bbc1-437d-becd-3f9a2d46dd6b.png");'>
      </div>
      <div class="flex h-full flex-1 flex-col items-start justify-start">
        <div class="flex w-full flex-row items-start justify-start gap-x-3">
          <p class="text-[#111418] text-sm font-bold leading-normal tracking-[0.015em]">Emily</p>
          <p class="text-[#637588] text-sm font-normal leading-normal">2 days ago</p>
        </div>
        <p class="text-[#111418] text-sm font-normal leading-normal">
          I agree, the database feature is one of the main reasons I use Notion. It's great for managing projects.
        </p>
        <div class="flex w-full flex-row items-center justify-start gap-9 pt-2">
          <div class="flex items-center gap-2">
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-yellow-400">★</span>
            <span class="text-gray-300">★</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Comment Form -->
    <div class="flex items-center px-4 py-3 gap-3">
      <label class="flex flex-col min-w-40 h-12 flex-1">
        <div class="flex w-full flex-1 items-stretch rounded-xl h-full">
          <input placeholder="Add public comment..."
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] focus:outline-0 focus:ring-0 border-none bg-[#f0f2f4] focus:border-none h-full placeholder:text-[#637588] px-4 rounded-r-none border-r-0 pr-2 text-base font-normal leading-normal"
            value="" />
          <div class="flex border-none bg-[#f0f2f4] items-center justify-center pr-4 rounded-r-xl border-l-0 !pr-2">
            <div class="flex items-center gap-4 justify-end">
              <div class="flex items-center gap-1">
                <button class="flex items-center justify-center p-1.5">
                  <div class="text-[#637588]" data-icon="SmileyWink" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                      viewBox="0 0 256 256">
                      <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216ZM80,108a12,12,0,1,1,12,12A12,12,0,0,1,80,108Zm104,0a8,8,0,0,1-8,8H152a8,8,0,0,1,0-16h24A8,8,0,0,1,184,108Zm-9.08,48c-10.29,17.79-27.39,28-46.92,28s-36.63-10. ```vue
2-46.93-28a8,8,0,1,1,13.86-8c7.46,12.91,19.2,20,33.07,20s25.61-7.1,33.08-20a8,8,0,1,1,13.84,8Z"></path>
                    </svg>
                  </div>
                </button>
              </div>
              <button
                class="min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#1980e6] text-white text-sm font-medium leading-normal hidden @[480px]:block">
                <span class="truncate">Comment</span>
              </button>
            </div>
          </div>
        </div>
      </label>
    </div>
  </div>
</template>

<script>
import apiClient from '@/helpers/axios';
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router';

export default {
  name: 'Domain',
  setup() {
    // Reactive state for the website
    const website = ref({
      name: '',
      domain: '',
      category: '',
      rank: 0,
      rank_change: 0
    })

    const route = useRoute();

    // Error handling
    const errorMessage = ref('')

    // Method to fetch website details
    const fetchWebsiteDetails = async (websiteName) => {
      try {
        // Assuming your API endpoint is structured like this
        const response = await apiClient.get(`api/websiteByName/${websiteName}`)

        // Update the website reactive ref with fetched data
        website.value = response.data

        // Clear any previous error messages
        errorMessage.value = ''
      } catch (error) {
        console.error("Failed to fetch website details:", error)
        errorMessage.value = 'Failed to load website details. Please try again later.'
      }
    }

    // Lifecycle hook to fetch data when component is mounted
    onMounted(() => {
      // You would typically get the website ID from route params
      const websiteName = route.params.domain_name
      fetchWebsiteDetails(websiteName)
    })

    // Expose to template
    return {
      website,
      errorMessage,
      fetchWebsiteDetails
    }
  }
}
</script>