
<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Credit Management</h1>
      <button class="btn btn-secondary">Add Credits</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card bg-emerald-50">
        <h3 class="text-emerald-800">Total Credits</h3>
        <p class="text-2xl font-bold text-emerald-600">1,500</p>
      </div>
      <div class="card bg-blue-50">
        <h3 class="text-blue-800">Active Users</h3>
        <p class="text-2xl font-bold text-blue-600">45</p>
      </div>
      <div class="card bg-purple-50">
        <h3 class="text-purple-800">Pending Transactions</h3>
        <p class="text-2xl font-bold text-purple-600">3</p>
      </div>
    </div>

    <div class="card">
      <h2 class="text-xl font-semibold mb-4">Credit Transactions</h2>
      <table class="w-full">
        <thead>
          <tr class="border-b">
            <th class="text-left py-3">User</th>
            <th class="text-left py-3">Amount</th>
            <th class="text-left py-3">Type</th>
            <th class="text-left py-3">Status</th>
            <th class="text-left py-3">Date</th>
            <th class="text-left py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="credit in credits" :key="credit.id" class="border-b">
            <td class="py-3">{{ credit.user }}</td>
            <td class="py-3">{{ credit.amount }} Credits</td>
            <td class="py-3">
              <span :class="[
                'badge',
                credit.type === 'Purchase' ? 'badge-success' : 'badge-warning'
              ]">
                {{ credit.type }}
              </span>
            </td>
            <td class="py-3">
              <span :class="[
                'badge',
                credit.status === 'Completed' ? 'badge-success' : 'badge-warning'
              ]">
                {{ credit.status }}
              </span>
            </td>
            <td class="py-3">{{ credit.date }}</td>
            <td class="py-3">
              <button class="text-blue-600 hover:text-blue-800">Details</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const credits = ref([
  {
    id: 1,
    user: 'John Doe',
    amount: 100,
    type: 'Purchase',
    status: 'Completed',
    date: '2024-03-01'
  },
  {
    id: 2,
    user: 'Jane Smith',
    amount: 50,
    type: 'Refund',
    status: 'Pending',
    date: '2024-03-02'
  },
]);
</script>

