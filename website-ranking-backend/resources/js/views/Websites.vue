<template>
    <div class="">
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Websites</h1>
                <button @click="showAddModal" class="btn btn-primary">Add Website</button>
            </div>

            <div class="card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3">Name</th>
                            <th class="text-left py-3">Domain</th>
                            <th class="text-left py-3">Category</th>
                            <th class="text-left py-3">Rank</th>
                            <th class="text-left py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="website in websites" :key="website.id" class="border-b">
                            <td class="py-3">{{ website.name }}</td>
                            <td class="py-3">{{ website.domain }}</td>
                            <td class="py-3">{{ website.categories.name }}</td>
                            <td class="py-3">#{{ website.rank }}</td>
                            <td class="py-3">
                                <button @click="showEditModal(website)"
                                    class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                                <button @click="showDeleteModal(website)"
                                    class="text-red-600 hover:text-red-800">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between">
                <button class="btn btn-secondary" @click="prevPage" :disabled="currentPage === 1">
                    Previous
                </button>
                <button class="btn btn-secondary" @click="nextPage" :disabled="websites.length < itemsPerPage">
                    Next
                </button>
            </div>
        </div>

        <!-- Add Website Modal -->
        <transition name="fade">
            <div v-if="isAddModalVisible"
                class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
                <div @click="isAddModalVisible = !isAddModalVisible" class="fixed bg-black opacity-70 inset-0 z-0">
                </div>
                <div
                    class="w-full max-w-lg p-3 relative max-h-full flex items-center mx-auto my-auto rounded-xl shadow-lg bg-white">
                    <form @submit.prevent="addWebsite" class="w-full">
                        <div class="p-3 flex-auto leading-6">
                            <h2 class="text-center text-lg font-bold">Add New Website</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Name
                                </label>
                                <input v-model="newWebsite.name" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Domain
                                </label>
                                <input v-model="newWebsite.domain" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select v-model="newWebsite.category_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option selected value="">Select category</option>
                                    <option v-for="category in categories" :value="category.id">{{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="p-3 mt-2 text -center space-x-4 md:block">
                            <button type="submit" class="mb-2 md:mb-0 btn btn-primary">
                                Add Website
                            </button>
                            <button type="reset" @click="cancelAddWebsite()" class="mb-2 md:mb-0 btn btn-danger">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Existing Edit Modal -->
        <transition name="fade">
            <div v-if="isModalVisible" class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
                <div @click="isModalVisible = !isModalVisible" class="fixed bg-black opacity-70 inset-0 z-0"></div>
                <div
                    class="w-full max-w-lg p-3 relative max-h-full flex items-center mx-auto my-auto rounded-xl shadow-lg bg-white">
                    <form @submit.prevent="updateWebsite" class="w-full">
                        <div class="p-3 flex-auto leading-6">
                            <h2 class="text-center text-lg font-bold">Edit Website</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Name
                                </label>
                                <input v-model="selectedWebsite.name" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Domain
                                </label>
                                <input v-model="selectedWebsite.domain" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select v-model="selectedWebsite.category_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none
                                    focus:ring-2 focus:ring-primary">
                                    <option selected value="">Select category</option>
                                    <option v-for="category in categories" :value="category.id">{{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="p-3 mt-2 text-center space-x-4 md:block">
                            <button type="submit" class="mb-2 md:mb-0 btn btn-primary">
                                Save
                            </button>
                            <button type="reset" @click="cancelEditWebsite()" class="mb-2 md:mb-0 btn btn-danger">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div v-if="isModalVisible2" class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
                <div @click="isModalVisible2 = !isModalVisible2" class="fixed bg-black opacity-70 inset-0 z-0"></div>
                <div
                    class="w-full max-w-lg p-3 relative max-h-full flex items-center mx-auto my-auto rounded-xl shadow-lg bg-white">
                    <div>
                        <div class="text-center p-3 flex-auto justify-center leading-6">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-16 h-16 flex items-center text-red-600 mx-auto" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h2 class="text-2xl font-bold py-4">Are you sure?</h2>
                            <p class="text-md text-gray-500 px-8">
                                Do you really want to delete <span class="underline text-red-600"> {{
                                    selectedWebsite.name }} </span> website?
                            </p>
                        </div>
                        <div class="p-3 mt-2 text-center space-x-4 md:block">
                            <button @click="deleteWebsite" class="mb-2 md:mb-0 btn btn-danger">
                                Delete
                            </button>
                            <button @click="cancelDeleteWebsite" class="mb-2 md:mb-0 btn btn-secondary">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';
import { useDataStore } from '../store/dataStore';
import { useToast } from 'vue-toastification';

export default {
    name: 'Websites',
    setup() {
        const state = reactive({
            websites: [],
            categories: [],
            currentPage: 1,
            itemsPerPage: 10,
            isModalVisible: false,
            isModalVisible2: false,
            isAddModalVisible: false,
            selectedWebsite: null,
            newWebsite: {
                name: '',
                domain: '',
                category_id: ''
            }
        });

        const dataStore = useDataStore();
        const toast = useToast();

        const getLstWebsites = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get(`/api/admin/websites`, {
                    params: {
                        page: state.currentPage,
                        limit: state.itemsPerPage
                    }
                });
                state.websites = response.data.data;
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const getCategories = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get('/api/admin/categories');
                state.categories = response.data.data;
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const showAddModal = () => {
            state.isAddModalVisible = true;
        }

        const cancelAddWebsite = () => {
            state.isAddModalVisible = false;
            state.newWebsite = { name: '', domain: '', category_id: '' };
        }

        const addWebsite = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.post('/api/admin/websites', state.newWebsite);
                if (response.code === 200) {
                    toast.success('Website added successfully');
                    getLstWebsites();
                    cancelAddWebsite();
                }
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const showEditModal = (website) => {
            state.isModalVisible = true;
            state.selectedWebsite = website;
        }

        const showDeleteModal = (website) => {
            state.isModalVisible2 = true;
            state.selectedWebsite = website;
        }

        const cancelEditWebsite = () => {
            state.isModalVisible = false;
            state.selectedWebsite = null;
        }

        const cancelDeleteWebsite = () => {
            state.isModalVisible2 = false;
            state.selectedWebsite = null;
        }

        const updateWebsite = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.put(`/api/admin/websites/${state.selectedWebsite.id}`, state.selectedWebsite);
                if (response.code === 200) {
                    toast.success('Website updated successfully');
                    getLstWebsites();
                    cancelEditWebsite();
                }
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const deleteWebsite = async () => {
            dataStore.setLoading(true);
            try {
                await apiClient.delete(`/api/admin/websites/${state.selectedWebsite.id}`);
                state.websites = state.websites.filter((website) => website.id !== state.selectedWebsite.id);
                state.isModalVisible2 = false;
                state.selectedWebsite = {};
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const nextPage = () => {
            state.currentPage++;
            getLstWebsites();
        };

        const prevPage = () => {
            if (state.currentPage > 1) {
                state.currentPage--;
                getLstWebsites();
            }
        };

        onMounted(() => {
            getLstWebsites();
            getCategories();
        });

        return {
            ...toRefs(state),
            nextPage,
            prevPage,
            showAddModal,
            cancelAddWebsite,
            addWebsite,
            showEditModal,
            showDeleteModal,
            cancelEditWebsite,
            cancelDeleteWebsite,
            updateWebsite,
            deleteWebsite,
        };
    },
};
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
