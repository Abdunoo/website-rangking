<template>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between gap-4 py-4">
      <!-- Header Section -->
      <div class="w-full md:w-auto">
        <header class="space-y-2">
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <span class="font-medium">#{{ website.rank }}</span>
            <span>•</span>
            <span>{{ website.category }}</span>
          </div>
          <h1 class="text-xl md:text-3xl font-bold">{{ website.name }}</h1>
          <p class="text-gray-600 text-sm md:text-base">{{ website.domain }}</p>
        </header>
      </div>
    </div>

    <!-- Ranking and Contact Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <section class="card">
        <h2 class="text-base font-semibold mb-3">Ranking Information</h2>
        <div class="space-y-2">
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Current Rank:</span>
            <span class="font-medium">#{{ website.rank }}</span>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Previous Rank:</span>
            <span class="font-medium">#{{ website.previous_rank || 'N/A' }}</span>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Rating:</span>
            <span class="font-medium">{{ website.rating || 'N/A' }}/5</span>
          </div>
        </div>
      </section>

      <section class="card">
        <h2 class="text-base font-semibold mb-3">Contact Information</h2>

        <template v-if="userHasAccess">
          <div v-for="contact in website.contacts" :key="contact.id"
            class="flex items-center justify-between py-2 border-b last:border-b-0">
            <span class="text-gray-600">
              {{ contact.type === 'email' ? 'Email' : 'Phone' }}
            </span>
            <span class="font-medium text-gray-900">
              {{ contact.value }}
            </span>
          </div>
        </template>

        <template v-else>
          <div class="text-center space-y-4">
            <p class="text-gray-500">
              Contact details are hidden. Unlock by purchasing credits.
            </p>
            <button @click="purchaseCredits" class="w-full bg-primary text-white py-3 rounded-lg 
             hover:bg-blue-600 transition-colors duration-300 
             focus:outline-none focus:ring-2 focus:ring-blue-400">
              Purchase Credits
            </button>
          </div>
        </template>
      </section>
    </div>

    <section class="mb-6">
      <h2 class="text-lg font-bold text-gray-800 mb-4">Report Contact Changes</h2>
      <form class="space-y-3">
        <input
          class="w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-gray-50 rounded-lg border"
          placeholder="Enter new email" />
        <input
          class="w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-gray-50 rounded-lg border"
          placeholder="Enter new phone number" />
        <textarea
          class="w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-gray-50 rounded-lg border"
          placeholder="Additional information"></textarea>
        <button class="w-full bg-primary text-white rounded-lg py-2 hover:bg-primary">
          Submit Report
        </button>
      </form>
    </section>

    <!-- User Reviews Section -->
    <section>
      <h3 class="text-lg font-bold mb-4">User Reviews</h3>

      <div v-for="review in reviews" :key="review.id" class="mb-4 p-3 bg-gray-50 rounded-lg">
        <div class="flex items-center mb-2">
          <img :src="review.userAvatar" class="w-8 h-8 rounded-full mr-3" />
          <div>
            <p class="font-semibold text-sm">{{ review.user.name }}</p>
            <p class="text-xs text-gray-500">{{ review.updated_at }}</p>
          </div>
        </div>
        <p class="text-sm mb-2">{{ review.content }}</p>
        <div class="flex items-center">
          <div class="flex">
            <span v-for="n in 5" :key="n" class="text-lg"
              :class="n <= review.rating ? 'text-yellow-400' : 'text-gray-300'">
              ★
            </span>
          </div>
        </div>
      </div>
    </section>

    <!-- Add Review Section -->
    <section class="mt-6">
      <h2 class="text-lg font-bold mb-4">Add Your Review</h2>
      <form @submit.prevent="submitReview" class="space-y-3">
        <div class="flex justify-center mb-4">
          <div class="flex space-x-2">
            <button v-for="i in 5" :key="i" type="button" @click="rating = i" class="text-2xl transition-colors"
              :class="rating >= i ? 'text-yellow-500' : 'text-gray-300'">
              ★
            </button>
          </div>
        </div>

        <textarea v-model="comment" class="w-full p-2 text-sm border rounded-lg min-h-[100px]"
          placeholder="Share your experience..."></textarea>

        <button type="submit" class="w-full bg-primary text-white rounded-lg py-2 hover:bg-primary">
          Submit Review
        </button>
      </form>
    </section>
  </div>
</template>

<script>
import apiClient from '@/helpers/axios';
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router';

export default {
  name: 'Domain',
  setup() {
    const website = ref({
      name: '',
      domain: '',
      category: '',
      rank: 0,
      previous_rank: null,
      contact: null,
      rating: 0
    });

    const credit = ref({
      amount: 100,
      description: 'Payment get access view contact'
    })

    const route = useRoute();
    const userHasAccess = ref(false);
    const rating = ref(4);
    const comment = ref('');
    const reviews = ref([]);

    const fetchWebsiteDetails = async (websiteName) => {
      try {
        const response = await apiClient.get(`api/websiteByName/${websiteName}`);
        website.value = response.data;
        userHasAccess.value = response.data.user.user_can_access_contact === 1;
        reviews.value = response.data.reviews || [];
      } catch (error) {
        console.error("Failed to fetch website details:", error);
      }
    };

    watch(() => route.params.domain, (websiteName) => {
      if (websiteName) {
        fetchWebsiteDetails(websiteName);
      }
    }, { immediate: true });

    const submitReview = async () => {
      try {
        const response = await apiClient.post('api/reviews', {
          website_id: website.value.id,
          rating: rating.value,
          content: comment.value,
        });
        if (response.code === 200) {
          reviews.value.push(response.data)
          comment.value= '';
        }
      } catch (error) {
        console.error("Failed to submit review:", error);
      }
    };

    const purchaseCredits = async () => {
      try {
        const response = await apiClient.post(`api/credits/deduct/${website.value.user.id}`, {
          amount: credit.value.amount,
          description: credit.value.description,
        });
        if (response.code === 200) {
          userHasAccess.value = true;
        }
      } catch (error) {
        console.error("Failed to get access view contact:", error);
      }
    };

    return {
      website,
      userHasAccess,
      rating,
      comment,
      reviews,
      submitReview,
      credit,
      purchaseCredits,
    };
  }
}
</script>

<style scoped>
body {
  @apply text-gray-800 leading-relaxed;
}

h1,
h2,
h3 {
  @apply font-semibold text-gray-900 tracking-tight;
}

.card {
  @apply bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md;
}

@media (max-width: 768px) {
  .layout-content-container {
    padding: 1rem;
  }
}

@media (prefers-color-scheme: dark) {}
</style>