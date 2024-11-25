<template>
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Review Moderation</h1>
        <div class="flex gap-2">
          <select v-model="filterStatus" class="input max-w-xs">
            <option value="all">All Reviews</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
          <button class="btn btn-purple">Bulk Actions</button>
        </div>
      </div>

      <div class="card">
        <table class="w-full">
          <thead>
            <tr class="border-b">
              <th class="text-left py-3">User</th>
              <th class="text-left py-3">Website</th>
              <th class="text-left py-3">Review</th>
              <th class="text-left py-3">Status</th>
              <th class="text-left py-3">Date</th>
              <th class="text-left py-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="review in reviews" :key="review.id" class="border-b">
              <td class="py-3">{{ review.user }}</td>
              <td class="py-3">{{ review.website }}</td>
              <td class="py-3">{{ review.content }}</td>
              <td class="py-3">
                <span :class="[
                  'badge',
                  review.status === 'Approved' ? 'badge-success' : 'badge-warning'
                ]">
                  {{ review.status }}
                </span>
              </td>
              <td class="py-3">{{ review.date }}</td>
              <td class="py-3 space-x-2">
                <button class="text-emerald-600 hover:text-emerald-800">Approve</button>
                <button class="text-red-600 hover:text-red-800">Reject</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>

<script setup>
import { ref } from 'vue';

const reviews = ref([
  {
    id: 1,
    user: 'John Doe',
    website: 'Example.com',
    content: 'Great website, very informative!',
    status: 'Approved',
    date: '2024-03-01'
  },
  {
    id: 2,
    user: 'Jane Smith',
    website: 'Sample.com',
    content: 'The design could be improved.',
    status: 'Pending',
    date: '2024-03-02'
  },
]);

const filterStatus = ref('all');
</script>

