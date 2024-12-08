<template>
  <div v-if="website" class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
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
            <button @click="purchaseCredits" class="w-full bg-primary text-white py-3 rounded-lg">
              Purchase Credits
            </button>
          </div>
        </template>
      </section>
    </div>

    <section class="mb-6">
      <h2 class="text-lg font-bold text-gray-800 mb-4">Report Contact Changes</h2>
      <form class="space-y-3" @submit.prevent="submitContactChanges">
        <input v-model="newEmail"
          class="w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-gray-50 rounded-lg border"
          placeholder="Enter new email" />
        <input v-model="newPhoneNumber" type="number"
          class="w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-gray-50 rounded-lg border"
          placeholder="Enter new phone number" />
        <button type="submit" class="w-full bg-primary text-white rounded-lg py-2 hover:bg-primary">
          Submit Report
        </button>
      </form>
    </section>

    <!-- User Reviews Section -->
    <section class="mb-44">
      <h3 class="text-lg font-bold mb-4">User Reviews</h3>
      <div v-for="review in reviews" :key="review.id" class="mb-4 p-3 bg-gray-50 rounded-lg">
        <div class="flex items-center mb-2">
          <img :src="review.user.photo ?? dataStore.defaultPicture" class="w-8 h-8 rounded-full object-cover mr-3" />
          <div>
            <p class="font-semibold text-sm">{{ review.user.name }}</p>
            <p class="text-xs text-gray-500">{{ formatDate(review.updated_at) }}</p>
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
    <section
      class="fixed bottom-10 md:bottom-0 left-0 right-0 flex justify-center items-center border border-gray-100 bg-white p-4 shadow-lg rounded-t-lg max-h-[250px] overflow-auto">
      <div class="w-full max-w-2xl">
        <h2 class="text-lg font-bold mb-2 text-center">Add Your Review</h2>
        <form @submit.prevent="submitReview" class="w-full">
          <div class="flex justify-center mb-2">
            <div class="flex space-x-1">
              <button v-for="i in 5" :key="i" type="button" @click="rating = i" class="text-3xl transition-colors"
                :class="rating >= i ? 'text-yellow-500' : 'text-gray-300'">
                ★
              </button>
            </div>
          </div>

          <!-- Input and Button in Flex Container -->
          <div class="flex items-center space-x-2">
            <textarea v-model="comment"
              class="flex-grow p-2 text-sm border rounded-lg min-h-[60px] max-h-[100px] resize-none focus:outline-none focus:ring-2 focus:ring-primary"
              placeholder="Share your experience..."></textarea>

            <button type="submit"
              class="bg-primary text-white rounded-lg py-2 px-4 hover:hover-bg-primary transition duration-200 ease-in-out">
              Submit
            </button>
          </div>
        </form>
      </div>
    </section>
  </div>
  <div v-else="website" class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
    <div class="text-center text-primary text-xl mt-4">
      Website not available
    </div>
  </div>
</template>

<script>
import apiClient from '@/helpers/axios';
import { useDataStore } from '@/store/dataStore.js';
import { reactive, toRefs, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';

export default {
  name: 'Domain',
  setup() {
    const state = reactive({
      website: {
        name: '',
        domain: '',
        category: '',
        rank: 0,
        previous_rank: null,
        contacts: [],
        rating: 0
      },
      credit: {
        amount: 100,
        description: 'Payment get access view contact'
      },
      userHasAccess: false,
      rating: 4,
      comment: '',
      reviews: [],
      newEmail: '',
      newPhoneNumber: '',
    });

    const dataStore = useDataStore();
    const route = useRoute();
    const toast = useToast();

    const fetchWebsiteDetails = async (websiteName) => {
      try {
        const response = await apiClient.get(`api/websiteByName/${websiteName}`);
        state.website = response.data;
        state.userHasAccess = response.data.view_contact;
        state.reviews = response.data.reviews || [];
      } catch (error) {
        state.website = null;
        console.error("Failed to fetch website details:", error);
      }
    };

    const submitReview = async () => {
      try {
        const response = await apiClient.post('api/reviews', {
          website_id: state.website.id,
          rating: state.rating,
          content: state.comment,
        });
        if (response.code === 200) {
          toast.success('Review submitted successfully, waiting approve from admin!');
          state.comment = '';
        }
      } catch (error) {
        toast.error('Failed to submit review');
        console.error("Failed to submit review:", error);
      }
    };

    const purchaseCredits = async () => {
      if (dataStore.credits < state.credit.amount) {toast.info('Your credit amount is insufficient!, please buy credit first'); return;}
      try {
        const response = await apiClient.post(`api/credits/deduct`, {
          website_id: state.website.id,
          amount: state.credit.amount,
          description: state.credit.description,
        });
        if (response.code === 200) {
          toast.success('Get access view contact successfully!');
          state.userHasAccess = true;
          dataStore.decreaseCredit(response.data.amount);
        }
      } catch (error) {
        toast.error('Failed to get access view contact')
        console.error("Failed to get access view contact:", error);
      }
    };

    const submitContactChanges = () => {
      if (state.newEmail.length < 2 && state.newPhoneNumber.length < 2) return;
      console.log('New Email:', state.newEmail);
      console.log('New Phone Number:', state.newPhoneNumber);

      state.newEmail = '';
      state.newPhoneNumber = '';

      toast.info('Contact changes not implemented yet!');
    }

    const formatDate = (datetime) => {
      const date = new Date(datetime);

      // Convert to US format: MM/DD/YYYY, HH:mm:ss AM/PM
      const options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true, // Enables AM/PM format
      };

      return date.toLocaleString("en-US", options);
    }

    const scrollToTop = () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    watch(() => route.params.domain, (websiteName) => {
      if (websiteName) {
        fetchWebsiteDetails(websiteName);
        scrollToTop();
      }
    }, { immediate: true });

    return {
      ...toRefs(state),
      submitReview,
      purchaseCredits,
      dataStore,
      formatDate,
      submitContactChanges,
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