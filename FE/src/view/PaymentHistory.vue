<template>
  <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="bg-primary text-white p-6">
        <h2 class="text-2xl font-bold">Payment History</h2>
      </div>
      <div class="p-6">
        <!-- Desktop Table View -->
        <div class="hidden sm:block">
          <table class="min-w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Date</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Amount</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Payment Method</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="payment in payments" :key="payment.id" class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">{{ formatDate(payment.created_at) }}</td>
                <td :class="{
                      'bg-secondary rounded-lg': payment.status === 'pending',
                      'bg-primary rounded-lg': payment.status === 'approved',
                      'bg-danger rounded-lg': payment.status === 'rejected',
                    }" class="px-4 py-2 text-white text-center">
                  {{ payment.status }}
                </td>
                <td class="px-4 py-2">{{ payment.amount }} Credits</td>
                <td class="px-4 py-2">{{ payment.payment_method ?? 'By Admin' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Card View -->
        <div class="sm:hidden">
          <div v-for="payment in payments" :key="payment.id" class="bg-white shadow-md rounded-lg mb-4 p-4">
            <div class="flex justify-between">
              <span class="font-bold text-gray-600">Date:</span>
              <span>{{ formatDate(payment.created_at) }}</span>
            </div>
            <div class="flex justify-between mt-2">
              <span class="font-bold text-gray-600">Status:</span>
              <span :class="{
                    'text-white bg-secondary rounded-lg': payment.status === 'pending',
                    'text-white bg-primary rounded-lg': payment.status === 'approved',
                    'text-white bg-danger rounded-lg': payment.status === 'rejected'
                  }" class="px-2 py-1 rounded">
                {{ payment.status }}
              </span>
            </div>
            <div class="flex justify-between mt-2">
              <span class="font-bold text-gray-600">Amount:</span>
              <span>${{ payment.amount.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between mt-2">
              <span class="font-bold text-gray-600">Payment Method:</span>
              <span>{{ payment.payment_method ?? 'By Admin' }}</span>
            </div>
          </div>
        </div>

        <div v-if="payments.length === 0" class="text-center text-gray-500 mt-4">
          No payment history available.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import apiClient from '@/helpers/axios';
import { useDataStore } from '@/store/dataStore';

export default {
  name: 'PaymentHistory',
  setup() {
    const payments = ref([]);
    const dataStore = useDataStore();

    const fetchPaymentHistory = async () => {
      dataStore.setLoading(true);
      try {
        const response = await apiClient.get('/api/credits/purchase-history');
        payments.value = response.data;
      } catch (error) {
        console.error('Error fetching payment history:', error);
      }
      dataStore.setLoading(false);
    };

    const formatDate = (datetime) => {
      const date = new Date(datetime);

      const options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true, 
      };

      return date.toLocaleString("en-US", options);
    }

    onMounted(() => {
      fetchPaymentHistory();
    });

    return {
      payments,
      formatDate,
    };
  },
};
</script>