<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Credit Management</h1>
            <button @click="isModalVisible = true" class="btn btn-secondary">Add Credits</button>
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
                                <button @click="approveCredit(credit.id)"
                                    class="text-green-600 hover:bg-green-100 rounded-md px-2 py-1">Approve</button>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-between">
            <button class="btn btn-secondary" @click="prevPage" :disabled="currentPage === 1">
                Previous
            </button>
            <button class="btn btn-secondary" @click="nextPage" :disabled="credits.length < itemsPerPage">
                Next
            </button>
        </div>
    </div>
    <transition name="fade">
        <div v-if="isModalVisible" class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
            <div @click="closeModal" class="fixed bg-black opacity-70 inset-0 z-0"></div>
            <div
                class="w-full max-w-lg p-6 relative max-h-full flex items-center mx-auto my-auto rounded-xl shadow-lg bg-white">
                <form @submit.prevent="addUserCredit" class="w-full">
                    <div class="flex-auto leading-6">
                        <h2 class="text-center text-xl font-bold mb-4">Add Amount User</h2>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                            <input v-model="creditForm.amount" type="text" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">User </label>
                            <input v-model="search" type="text" @input="searchUser(search)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary mb-2" />
                            <select v-model="creditForm.userId"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 text-center space-x-4">
                        <button type="submit" class="mb-2 btn btn-primary px-4 py-2 rounded-md">
                            {{ 'Add Credits Amount' }}
                        </button>
                        <button type="button" @click="isModalVisible = false"
                            class="mb-2 btn btn-danger px-4 py-2 rounded-md">Close</button>
                    </div>
                </form>
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
            credits: [],
            totalCredits: 0,
            pendingTransactions: 0,
            activeUsers: 0,
            showMenu: false,
            activeMenuId: null,
            currentPage: 1,
            itemsPerPage: 10,
            users: [],
            search: '',
            isModalVisible: false,
            creditForm: {
                amount: '',
                userId: '',
            }
        });

        const dataStore = useDataStore();
        const toast = useToast();

        const getLstCredits = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get('/api/admin/credits', {
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
            dataStore.setLoading(false);
        }

        const toggleMenu = (creditId) => {
            state.activeMenuId = state.activeMenuId === creditId ? null : creditId;
        }

        const approveCredit = async (creditId) => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get(`/api/admin/credits/${creditId}/approve`);
                if (response.code === 200) {
                    state.credits = state.credits.map(credit => credit.id === creditId ? response.data : credit);
                    toast.success('Credit approved successfully');
                    getLstCredits();
                }
            } catch (error) {
                toast.error(error.response.data.message || 'Error approving credit')
                console.error(error);
            }
            state.activeMenuId = null;
            dataStore.setLoading(false);
        }

        const addUserCredit = async () => {
            dataStore.setLoading(true
            );
            try {
                const response = await apiClient.post('/api/admin/credits/add', {
                    userId: state.creditForm.userId,
                    amount: state.creditForm.amount,
                });
                if (response.code === 200) {
                    state.credits = [...state.credits, response.data];
                    toast.success('Credit added successfully');
                    getLstCredits();
                }
            } catch (error) {
                console.error(error);
            } finally {
                dataStore.setLoading(false);
            }
        }

        const searchUser = async (searchTerm) => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get(`/api/admin/users/search?searchTerm=${searchTerm}`);
                if (response.code === 200) {
                    console.log(response.data)
                    state.users = response.data;
                }
            } catch (error) {
                console.error(error);
            } finally {
                dataStore.setLoading(false);
            }
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
            addUserCredit,
            searchUser,
        }

    }
}
</script>
