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
              <td class="py-3">{{ review.user.name }}</td>
              <td class="py-3">{{ review.website.name }}</td>
              <td class="py-3">{{ review.content ?? '-' }}</td>
              <td class="py-3">
                <span :class="[
                  'badge',
                  review.is_approved === 1 ? 'badge-success' : 'badge-warning'
                ]">
                  {{ review.is_approved === 1 ? 'Approved' : 'Pending' }}
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
      <div class="flex justify-between">
            <button
                class="btn btn-secondary"
                @click="prevPage"
                :disabled="currentPage === 1">
                Previous
            </button>
            <button
                class="btn btn-secondary"
                @click="nextPage"
                :disabled="reviews.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
  </template>

<script>
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';
import { useDataStore } from '../store/dataStore';

export default {
    setup() {
        const state = reactive({
            reviews: [],
            currentPage: 1,
            itemsPerPage: 10,
        });

        const dataStore = useDataStore();

        const getLstReviews = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get('/api/admin/reviews', {
                    params: {
                        page: state.currentPage,
                        limit: state.itemsPerPage
                    }
                });
                state.reviews = response.data.data;
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        }

        const nextPage = () => {
            state.currentPage++;
            getLstReviews();
        };

        const prevPage = () => {
            if (state.currentPage > 1) {
                state.currentPage--;
                getLstReviews();
            }
        };

        onMounted(() => {
            getLstReviews();
        })

        return {
            ...toRefs(state),
            nextPage,
            prevPage,
        }

    }
}
</script>

