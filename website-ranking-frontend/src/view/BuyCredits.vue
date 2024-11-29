<template>
  <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
      <div class="flex justify-between items-center bg-primary text-white p-6 ">
        <div class="">
          <h1 class="text-2xl font-bold">Purchase Credits</h1>
          <p class="text-sm text-white/80 mt-2">
            Buy credits to unlock contact information and access premium features
          </p>
        </div>
        <RouterLink to="/payment-history"
          class="bg-white text-primary text-sm px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
          Payment History
        </RouterLink>
      </div>

      <div class="p-6">
        <!-- Credit Package Selection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div v-for="(pack, index) in creditPacks" :key="index" @click="selectPack(pack)" :class="[
            'border rounded-lg p-4 cursor-pointer transition-all duration-300',
            selectedPack === pack
              ? 'border-primary bg-primary/10 ring-2 ring-primary'
              : 'border-gray-200 hover:bg-gray-50'
          ]">
            <div class="text-center">
              <h3 class="text-lg font-bold text-primary">{{ pack.credits }} Credits</h3>
              <p class="text-xl font-bold mt-2">
                ${{ pack.price.toFixed(2) }}
              </p>
              <p v-if="pack.bonus" class="text-sm text-success mt-1">
                + {{ pack.bonus }} Bonus
              </p>
            </div>
          </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-4">Select Payment Method</h2>
          <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <button v-for="method in paymentMethods" :key="method.id" @click="selectPaymentMethod(method)" :class="[
              'flex flex-col items-center justify-center p-4 border rounded-lg shadow-sm transition-all duration-300',
              selectedPaymentMethod === method
                ? 'border-primary bg-primary/10 ring-2 ring-primary'
                : 'border-gray-200 hover:bg-gray-50'
            ]">
              <div v-html="method.icon" class="mb-2"></div>
              <span class="text-sm font-medium text-gray-700">{{ method.name }}</span>
            </button>
          </div>
        </div>

        <!-- Optional Message to Admin -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Optional Message to Admin
          </label>
          <textarea v-model="adminMessage" rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Have a specific request or need? Let us know..."></textarea>
        </div>

        <!-- Total and Purchase Button -->
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="flex justify-between items-center mb-2">
            <span>Credits:</span>
            <span>{{ selectedPack?.credits || 0 }}</span>
          </div>
          <div class="flex justify-between items-center mb-4">
            <span class="font-bold">Total:</span>
            <span class="text-xl font-bold text-primary">
              ${{ (selectedPack?.price || 0).toFixed(2) }}
            </span>
          </div>
          <button @click="processPurchase" :disabled="!isReadyToPurchase" class="w-full bg-primary text-white py-3 rounded-lg hover-bg-primary transition duration-300 
            disabled:opacity-50 disabled:cursor-not-allowed">
            Complete Purchase
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '@/helpers/axios'
import { useToast } from 'vue-toastification';
import { useDataStore } from '@/store/dataStore';

// Credit Pack Definitions
const creditPacks = [
  { credits: 50, price: 9.99, bonus: 5 },
  { credits: 100, price: 19.99, bonus: 15 },
  { credits: 250, price: 49.99, bonus: 50 }
]

// Payment Method Definitions with SVG Icons
const paymentMethods = [
  {
    id: 'paypal',
    name: 'PayPal',
    icon: `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="48" height="48">
            <path fill="#003087" d="M10.5 11.5h11c2.2 0 4 1.8 4 4v1c0 2.2-1.8 4-4 4h-11v-9z"/>
            <path fill="#009CDE" d="M21.5 15.5c0-2.2-1.8-4-4-4h-7v12h7c2.2 0 4-1.8 4-4v-4z"/>
            <path fill="#012169" d="M10.5 20.5v-9h-4c-2.2 0-4 1.8-4 4v1c0 2.2 1.8 4 4 4h4z"/>
        </svg>

      `
  },
  {
    id: 'credit_card',
    name: 'Credit Card',
    icon: `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
  <path fill="#1976D2" d="M40 4H8C5.8 4 4 5.8 4 8v32c0 2.2 1.8 4 4 4h32c2.2 0 4-1.8 4-4V8c0-2.2-1.8-4-4-4z"/>
  <path fill="#FFF" d="M4 16h40v8H4z"/>
  <path fill="#2196F3" d="M4 24h40v8H4z"/>
</svg>

      `
  },
  {
    id: 'stripe',
    name: 'Stripe',
    icon: `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
  <path fill="#6772E5" d="M24 0C10.7 0 0 10.7 0 24s10.7 24 24 24 24-10.7 24-24S37.3 0 24 0z"/>
  <path fill="#FFF" d="M19.2 25.3c0-1.1.9-1.5 2.4-1.5 2.1 0 4.8.6 6.9 1.8V18c-2.3-.9-4.6-1.3-6.9-1.3-5.5 0-9.2 2.8-9.2 7.5 0 7.3 10 6.1 10 9.2 0 1.2-.9 1.6-2.5 1.6-2.2 0-5-.9-7.2-2.1v6.8c2.5 1.1 5 1.5 7.2 1.1 1.1 2.5 1.1 2.5 1.1 1.1 0 1.5-.9 1.5-2.4 0-2.1-.6-4.8-1.8-6.9z"/>
</svg>

      `
  }
]

const selectedPack = ref(null)
const selectedPaymentMethod = ref(null)
const adminMessage = ref('')
const router = useRouter()
const toast = useToast();
const dataStore = useDataStore();

const selectPack = (pack) => {
  selectedPack.value = pack
}

const selectPaymentMethod = (method) => {
  selectedPaymentMethod.value = method
}

const isReadyToPurchase = computed(() => {
  return selectedPack.value && selectedPaymentMethod.value
})

const processPurchase = async () => {
  dataStore.setLoading(true);
  try {
    const response = await apiClient.post('/api/credits/purchase-credits', {
      amount: selectedPack.value.credits,
      payment_method: selectedPaymentMethod.value.name,
      description: adminMessage.value
    })

    if (response.code === 200) {
      toast.success(`Successfully purchase!, waiting approve from admin`)
      router.push('/payment-history');
    }
  } catch (error) {
    console.error('Purchase failed:', error)
    toast.error('Purchase failed. Please try again.')
  }
  dataStore.setLoading(false);
}
</script>

<style scoped></style>