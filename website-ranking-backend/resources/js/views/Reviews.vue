<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Review Moderation</h1>
            <div class="flex gap-2">
                <button @click="showModalUpdate('approve')" class="btn btn-primary">Approve All</button>
                <button @click="showModalUpdate('reject')" class="btn btn-danger">Reject All</button>
            </div>
        </div>

        <div class="card">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">User </th>
                        <th class="text-left py-3">Website</th>
                        <th class="text-left py-3">Rating</th>
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
                        <td class="py-3">{{ review.rating + ' Star' }}</td>
                        <td class="py-3">{{ review.content ?? '-' }}</td>
                        <td class="py-3">
                            <span :class="[
                                'badge',
                                review.is_approved === 1 ? 'badge-success' : 'badge-warning'
                            ]">
                                {{ review.is_approved === 1 ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td class="py-3">{{ review.created_at }}</td>
                        <td class="py-3 space-x-2">
                            <button class="text-emerald-600 hover:text-emerald-800" @click="approveReview(review.id)">
                                Approve
                            </button>
                            <button class="text-red-600 hover:text-red-800" @click="rejectReview(review.id)">
                                Reject</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-between">
            <button class="btn btn-secondary" @click="prevPage" :disabled="currentPage === 1">
                Previous
            </button>
            <button class="btn btn-secondary" @click="nextPage" :disabled="reviews.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
    <transition name="fade">
            <div v-if="isModalVisible" class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
                <div @click="isModalVisible = false" class="fixed bg-black opacity-70 inset-0 z-0"></div>
                <div class="w-full max-w-lg p-3 relative max-h-full flex items-center mx-auto my-auto rounded-xl shadow-lg bg-white">
                    <div>
                        <div class="text-center p-3 flex-auto justify-center leading-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 flex items-center text-red-600 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 2a1 1 0 011 1v10a1 1 0 01-2 0V3a1 1 0 011-1zm0 16a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                <path d="M12 0a12 12 0 00-12 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0zm0 22a10 10 0 110-20 10 10 0 010 20z" />
                            </svg>
                            <h2 class="text-2xl font-bold py-4">Are you sure?</h2>
                            <p class="text-md text-gray-700 px-8">
                                Do you really want to Update Ranking All Websites?
                            </p>
                            <p class="text-md text-red-700 px-8">This may take some time</p>
                        </div>
                        <div class="p-3 mt-2 text-center space-x-4 md:block">
                            <button @click="isModalVisible = false" class="mb-2 md:mb-0 btn btn-secondary">
                                Close
                            </button>
                            <button @click="updateAllReview" class="mb-2 md:mb-0 btn btn-primary">
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
</template>

<script>
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';
import { useDataStore } from '../store/dataStore';
import { useToast } from 'vue-toastification';

export default {
    setup() {
        const state = reactive({
            reviews: [],
            currentPage: 1,
            itemsPerPage: 10,
            isModalVisible: false,
            setStatus: false,
        });

        const dataStore = useDataStore();
        const toast = useToast();

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

        const approveReview = async (reviewId) => {
            dataStore.setLoading(true);
            try {
                await apiClient.get(`/api/admin/reviews/${reviewId}/approve`);
                toast.success('Review approved successfully')
                await getLstReviews();
            } catch (error) {
                toast.error(error.response.data.message)
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const rejectReview = async (reviewId) => {
            dataStore.setLoading(true);
            try {
                await apiClient.get(`/api/admin/reviews/${reviewId}/reject`);
                toast.success('Review rejected successfully')
                await getLstReviews();
            } catch (error) {
                toast.error(error.response.data.message);
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const updateAllReview = async() => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.post('/api/admin/reviews/update-all', { status : state.setStatus });
                if (response.code === 200) {
                    toast.success('Reviews updated successfully');
                    await getLstReviews();
                }
            } catch (error) {
                toast.error(error.response.data.message
                );
                console.error(error);
            }
            dataStore.setLoading(false
            );
        }

        const showModalUpdate = async(status) => {
            state.setStatus = status;
            await updateAllReview();
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
            approveReview,
            rejectReview,
            updateAllReview,
            showModalUpdate,
        }

    }
}
</script>
