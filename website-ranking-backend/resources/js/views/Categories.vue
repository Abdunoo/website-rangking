<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Categories</h1>
            <button class="btn btn-primary" @click="showAddCategoryModal">Add Category</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div v-for="category in categories" :key="category.id" class="card">
                <h3 class="text-xl font-semibold">{{ category.name }}</h3>
                <p class="text-gray-600 mt-2">{{ category.websiteCount }} websites</p>
                <div class="mt-4 flex gap-2">
                    <button class="btn bg-gray-100 hover:bg-gray-200"
                        @click="showEditCategoryModal(category)">Edit</button>
                    <button class="btn bg-red-100 text-red-600 hover:bg-red-200"
                        @click="deleteCategory(category.id)">Delete</button>
                </div>
            </div>
        </div>

        <!-- Add/Edit Category Modal -->
        <transition name="fade">
            <div v-if="isModalVisible" class="fixed inset-0 flex items-center justify-center z-10">
                <div @click="isModalVisible = false" class="fixed inset-0 bg-black opacity-70"></div>
                <div class="w-full max-w-lg p-6 relative mx-auto my-auto rounded-xl shadow-lg bg-white">
                    <h2 class="text-center text-2xl font-semibold mb-4">{{ isEditMode ? 'Edit' : 'Add' }} Category</h2>
                    <form @submit.prevent="isEditMode ? updateCategory() : createCategory()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="categoryName">Category Name</label>
                            <input v-model="categoryForm.name" id="categoryName" type="text" placeholder="Enter category name"
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
import { onMounted, reactive, ref, toRefs } from 'vue';
import apiClient from '../helpers/axios';
import { useDataStore } from '../store/dataStore';
import { useToast } from 'vue-toastification';

export default {
    name: 'Categories',
    setup() {
        const state = reactive({
            categories: [],
            isModalVisible: false,
            isEditMode: false,
            categoryForm: {
                id: null,
                name: ''
            }
        });

        const dataStore = useDataStore();
        const toast = useToast();

        const getLstcategories = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get(`/api/admin/categories`);
                state.categories = response.data.data;
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const showAddCategoryModal = () => {
            state.isEditMode = false;
            state.categoryForm = { id: null, name: '' };
            state.isModalVisible = true;
        };

        const showEditCategoryModal = (category) => {
            state.isEditMode = true;
            state.categoryForm = { id: category.id, name: category.name };
            state.isModalVisible = true;
        };

        const closeModal = () => {
            state.isModalVisible = false;
        };

        const createCategory = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.post('/api/admin/categories', { name: state.categoryForm.name });
                state.categories.push(response.data);
                closeModal();
                toast.success('Category created successfully')
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const updateCategory = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.put(`/api/admin/categories/${state.categoryForm.id}`, { name: state.categoryForm.name });
                const index = state.categories.findIndex(category => category.id === state.categoryForm.id);
                state.categories[index] = response.data;
                closeModal();
                toast.success('Category updated successfully')
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        const deleteCategory = async (id) => {
            dataStore.setLoading(true);
            try {
                await apiClient.delete(`/api/admin/categories/${id}`);
                state.categories = state.categories.filter(category => category.id !== id);
                toast.success('Success delete category')
            } catch (error) {
                console.error(error);
            }
            dataStore.setLoading(false);
        };

        onMounted(() => {
            getLstcategories();
        });

        return {
            ...toRefs(state),
            showAddCategoryModal,
            showEditCategoryModal,
            closeModal,
            createCategory,
            updateCategory,
            deleteCategory
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
