<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mx-auto bg-white shadow-md rounded-lg overflow-hidden">
      <div class="bg-primary flex items-center justify-between text-white p-6">
        <h2 class="text-2xl font-bold">User Profile
        </h2>
        <RouterLink to="/payment-history"
            class="bg-white text-primary text-sm px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
            Payment History
          </RouterLink>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-secondary font-semibold mb-2">Profile Photo</label>
            <div class="flex items-center">
              <img :src="dataStore.profilePhoto" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover mr-4">
            </div>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Name</label>
            <p class="text-gray-800 bg-gray-100 p-2 rounded">{{ dataStore.user?.name }}</p>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Email</label>
            <p class="text-gray-800 bg-gray-100 p-2 rounded">{{ dataStore.user?.email }}</p>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Role</label>
            <p class="text-gray-800 bg-gray-100 p-2 rounded">{{ dataStore.user?.role }}</p>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Credits</label>
            <p class="text-success font-bold bg-gray-100 p-2 rounded">
              {{ dataStore.credits }} Credits
            </p>
          </div>
        </div>

        <div class="mt-6 flex space-x-4 text-sm">
          <button @click="openEditProfileModal"
            class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
            Edit Profile
          </button>

          <button @click="redirectToChangePassword"
            class="bg-danger text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
            Change Password
          </button>

          <button @click="logout" class="bg-danger text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
            Logout
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <div v-if="showEditProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-xl font-bold mb-4">Edit Profile</h3>
        <div class="mb-4">
          <label class="block mb-2">Name</label>
          <input type="text" v-model="dataStore.user.name" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
          <label class="block mb-2">Email</label>
          <input type="email" v-model="dataStore.user.email" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
          <label class="block mb-2">Profile Photo</label>
          <input type="file" @change="onFileChange" accept="image/*" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="flex justify-between">
          <button @click="saveProfile" class="bg-primary text-white px-4 py-2 rounded">
            Save Changes
          </button>
          <button @click="closeEditProfileModal" class="bg-danger text-white px-4 py-2 rounded">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import apiClient from '@/helpers/axios';
import router from '@/router';
import { useDataStore } from '@/store/dataStore.js';
import { useToast } from 'vue-toastification';

export default {
  setup() {
    const showEditProfileModal = ref(false);
    const selectedFile = ref(null);
    const dataStore = useDataStore();
    const toast = useToast();

    const openEditProfileModal = () => {
      showEditProfileModal.value = true;
    };

    const closeEditProfileModal = () => {
      showEditProfileModal.value = false;
    };

    const saveProfile = async () => {
      try {
        const formData = new FormData();
        formData.append('name', dataStore.user.name);
        formData.append('email', dataStore.user.email);
        if (selectedFile.value) {
          formData.append('photo', selectedFile.value);
        }

        const response = await apiClient.put('api/auth/profile', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        if (response.code === 200) {
          toast.success('Profile updated successfully');
          closeEditProfileModal();
          await dataStore.fetchUserData()
        }
      } catch (error) {
        toast.error('Profile updated failed');
        console.error(error);
      }
    };

    const onFileChange = (event) => {
      selectedFile.value = event.target.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        dataStore.user.photo = e.target.result;
      };
      reader.readAsDataURL(selectedFile.value);
    };

    const redirectToChangePassword = () => {
      router.push('/change-password');
    };

    const logout = () => {
      router.push('/login');
      localStorage.removeItem('token');
      localStorage.removeItem('_usr');
      dataStore.removeCredits();
      dataStore.removePhotoProfile();
      toast.info('Logout Successfully!')
    };

    onMounted(()=>{
      dataStore.fetchUserData();
    })

    return {
      dataStore,
      showEditProfileModal,
      selectedFile,
      openEditProfileModal,
      closeEditProfileModal,
      saveProfile,
      onFileChange,
      redirectToChangePassword,
      logout
    };
  }
}
</script>

<style scoped>
/* Additional custom styles if needed */
</style>