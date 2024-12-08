<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">User  Management</h1>
            <button class="btn btn-primary" @click="showAddUserModal">Add User</button>
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
                            <button v-on:click="showEditUserModal(user)" class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add/Edit User Modal -->
        <transition name="fade">
            <div v-if="isModalVisible" class="fixed inset-0 flex items-center justify-center z-10">
                <div @click="closeModal" class="fixed inset-0 bg-black opacity-70"></div>
                <div class="w-full max-w-lg p-6 relative mx-auto my-auto rounded-xl shadow-lg bg-white">
                    <h2 class="text-center text-2xl font-semibold mb-4">{{ isEditMode ? 'Edit' : 'Add' }} User</h2>
                    <form @submit.prevent="isEditMode ? updateUser () : createUser ()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="userName">Name</label>
                            <input v-model="userForm.name" id="userName" type="text" placeholder="Enter user name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="userEmail">Email</label>
                            <input v-model="userForm.email" id="userEmail" type="email" placeholder="Enter user email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="userRole">Role</label>
                            <input v-model="userForm.role" id="userRole" type="text" placeholder="Enter user role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required />
                        </div>
                        <div v-if="!isEditMode">
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="userPassword">Password</label>
                            <input v-model="userForm.password" id="userPassword" type="password" placeholder="Enter user password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required />
                        </div>
                        <div class="p-3 mt-2 text-center space-x-4 md:block">
                            <button type="submit" class="mb-2 md:mb-0 btn btn-primary">
                                {{ isEditMode ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" @click="closeModal" class="mb-2 md:mb-0 btn btn-danger">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue';
import apiClient from '../helpers/axios';
import { useDataStore } from '../store/dataStore';
import { useToast } from 'vue-toastification';

export default {
    name: 'User Management',
    setup() {
        const state = reactive({
            users: [],
            isModalVisible: false,
            isEditMode: false,
            userForm: {
                id: null,
                name : '',
                email: '',
                role: '',
                password: '' // Added password field
            }
        });

        const dataStore = useDataStore();
        const toast = useToast();

        const getUsers = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get('/api/admin/users');
                state.users = response.data.data;
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const showAddUserModal = () => {
            state.isEditMode = false;
            state.userForm = { id: null, name: '', email: '', role: '', password: '' }; // Reset password field
            state.isModalVisible = true;
        };

        const showEditUserModal = (user) => {
            state.isEditMode = true;
            state.userForm = { id: user.id, name: user.name, email: user.email, role: user.role, password: '' }; // Password not shown in edit mode
            state.isModalVisible = true;
        };

        const closeModal = () => {
            state.isModalVisible = false;
        };

        const createUser  = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.post('/api/admin/users', state.userForm);
                state.users.push(response.data);
                closeModal();
                toast.success('User  created successfully');
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const updateUser  = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.put(`/api/admin/users/${state.userForm.id}`, state.userForm);
                const index = state.users.findIndex(user => user.id === state.userForm.id);
                state.users[index] = response.data;
                closeModal();
                toast.success('User  updated successfully');
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        onMounted(() => {
            getUsers();
        });

        return {
            ...toRefs(state),
            showAddUserModal,
            showEditUserModal,
            closeModal,
            createUser ,
            updateUser ,
        };
    }
}
</script>

<style>
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 500ms ease-out;
}
</style>
