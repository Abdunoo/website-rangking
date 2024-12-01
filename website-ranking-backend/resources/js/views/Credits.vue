<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Credit Management</h1>
            <button class="btn btn-secondary">Add Credits</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card bg-emerald-50">
                <h3 class="text-emerald-800">Total Credits</h3>
                <p class="text-2xl font-bold text-emerald-600">{{ totalCredits }}</p>
            </div>
            <div class="card bg-blue-50">
                <h3 class="text-blue-800">Active Users</h3>
                <p class="text-2xl font-bold text-blue-600">{{ activeUsers }}</p>
            </div>
            <div class="card bg-purple-50">
                <h3 class="text-purple-800">Pending Transactions</h3>
                <p class="text-2xl font-bold text-purple-600">{{ pendingTransactions }}</p>
            </div>
        </div>

        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Credit Transactions</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">User </th>
                        <th class="text-left py-3">Amount</th>
                        <th class="text-left py-3">Type</th>
                        <th class="text-left py-3">Status</th>
                        <th class="text-left py-3">Date</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="credit in credits" :key="credit.id" class="border-b">
                        <td class="py-3">{{ credit.user?.name }}</td>
                        <td class="py-3">{{ credit.amount }} Credits</td>
                        <td class="py-3">
                            <span :class="[
                                'badge',
                                credit.type === 'purchase' ? 'badge-success' : 'badge-warning'
                            ]">
                                {{ credit.type }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span :class="[
                                'badge',
                                credit.status === 'approved' ? 'badge-success' : 'badge-warning'
                            ]">
                                {{ credit.status }}
                            </span>
                        </td>
                        <td class="py-3">{{ credit.created_at }}</td>
                        <td class="py-3">
                            <div class="relative inline-block text-left">
                                <button class="text-blue-600 hover:text-blue-800" @click="toggleMenu(credit.id)">
                                    Details
                                </button>

                                <div v-if="activeMenuId === credit.id"
                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-10">
                                    <div class="py-2">
                                        <p class="px-4 text-gray-700">Do you want to approve or reject the credit?</p>
                                        <div class="flex justify-between px-4 py-2">
                                            <button @click="approveCredit(credit.id)"
                                                class="text-green-600 hover:bg-green-100 rounded-md px-2 py-1">Approve</button>
                                            <button @click="rejectCredit(credit.id)"
                                                class="text-red-600 hover:bg-red-100 rounded-md px-2 py-1">Reject</button>
                                        </div>
                                        <div class="px-4 py-1">
                                            <button @click="toggleMenu(null)"
                                                class="text-gray-600 hover:bg-gray-200 rounded-md px-2 py-1">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                :disabled="credits.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';

export default {
    setup() {
        const state = reactive({
            credits: [],
            totalCredits: 0,
            pendingTransactions: 0,
            activeUsers: 0,
            showMenu: false,
            activeMenuId: null,
            currentPage: 1,
            itemsPerPage: 10,
        });

        const getLstCredits = async () => {
            try {
                const response = await apiClient.get('/api/admin/credits',{
                    params: {
                        page: state.currentPage,
                        limit: state.itemsPerPage,
                    }
                });
                state.credits = response.data.credits.data;
                state.totalCredits = response.data.totalCredits;
                state.activeUsers = response.data.activeUsers;
                state.pendingTransactions = response.data.pendingTransactions;
            } catch (error) {
                console.error(error);
            }
        }

        const toggleMenu = (creditId) => {
            state.activeMenuId = state.activeMenuId === creditId ? null : creditId;
        }

        const approveCredit = async(creditId) => {
            try {
                const response = await apiClient.get(`/api/admin/credits/${creditId}/approve`);
                if (response.code === 200) {
                    state.credits = state.credits.map(credit => credit.id === creditId ? response.data : credit);
                    getLstCredits();
                }
            } catch (error) {
                console.error(error);
            }
            state.activeMenuId = null;
        }
        const rejectCredit = (creditId) => {
            // Logic to reject the credit
            console.log(`Credit ${creditId} rejected`);
            state.activeMenuId = null; // Hide the menu after action
        }

        const nextPage = () => {
            state.currentPage++;
            getLstCredits();
        };

        const prevPage = () => {
            if (state.currentPage > 1) {
                state.currentPage--;
                getLstCredits();
            }
        };

        onMounted(() => {
            getLstCredits();
        })

        return {
            ...toRefs(state),
            toggleMenu,
            approveCredit,
            rejectCredit,
            nextPage,
            prevPage,
        }

    }
}
</script>
