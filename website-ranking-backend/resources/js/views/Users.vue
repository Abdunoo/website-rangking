<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">User Management</h1>
            <button class="btn btn-primary">Add User</button>
        </div>

        <div class="card">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Name</th>
                        <th class="text-left py-3">Email</th>
                        <th class="text-left py-3">Role</th>
                        <th class="text-left py-3">Join Date</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-b">
                        <td class="py-3">{{ user.name }}</td>
                        <td class="py-3">{{ user.email }}</td>
                        <td class="py-3">
                            <span class="badge badge-success">{{ user.role }}</span>
                        </td>
                        <td class="py-3">{{ user.created_at }}</td>
                        <td class="py-3">
                            <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                            <button class="text-red-600 hover:text-red-800">Deactivate</button>
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
                :disabled="users.length < itemsPerPage">
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
            users: [],
            currentPage: 1,
            itemsPerPage: 10,
        });

        const getLstUsers = async () => {
            try {
                const response = await apiClient.get('/api/admin/users', {
                    params: {
                        page: state.currentPage,
                        limit: state.itemsPerPage
                    }
                });
                state.users = response.data.data;
            } catch (error) {
                console.error(error);
            }
        }

        const nextPage = () => {
            state.currentPage++;
            getLstUsers();
        };

        const prevPage = () => {
            if (state.currentPage > 1) {
                state.currentPage--;
                getLstUsers();
            }
        };

        onMounted(() => {
            getLstUsers();
        })

        return {
            ...toRefs(state),
            nextPage,
            prevPage,
        }

    }
}
</script>
