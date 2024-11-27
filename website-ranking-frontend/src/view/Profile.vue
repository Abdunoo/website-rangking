<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mx-auto bg-white shadow-md rounded-lg overflow-hidden">
      <div class="bg-primary text-white p-6">
        <h2 class="text-2xl font-bold">User Profile</h2>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-secondary font-semibold mb-2">Profile Photo</label>
            <div class="flex items-center">
              <img :src="user.photo" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover mr-4">
            </div>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Name</label>
            <p class="text-gray-800 bg-gray-100 p-2 rounded">{{ user.name }}</p>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Email</label>
            <p class="text-gray-800 bg-gray-100 p-2 rounded">{{ user.email }}</p>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Role</label>
            <p class="text-gray-800 bg-gray-100 p-2 rounded">{{ user.role }}</p>
          </div>

          <div>
            <label class="block text-secondary font-semibold mb-2">Credits</label>
            <p class="text-success font-bold bg-gray-100 p-2 rounded">
              {{ user.credits }} Credits
            </p>
          </div>
        </div>

        <div class="mt-6 flex space-x-4">
          <button @click="openEditProfileModal"
            class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
            Edit Profile
          </button>

          <button @click="redirectToChangePassword"
            class="bg-secondary text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors">
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
          <input type="text" v-model="user.name" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
          <label class="block mb-2">Email</label>
          <input type="email" v-model="user.email" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
          <label class="block mb-2">Profile Photo</label>
          <input type="file" @change="onFileChange" accept="image/*" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="flex justify-between">
          <button @click="saveProfile" class="bg-primary text-white px-4 py-2 rounded">
            Save Changes
          </button>
          <button @click="closeEditProfileModal" class="bg-secondary text-white px-4 py-2 rounded">
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
import defaultPicture from '@/assets/images/photo_profile.webp';
import { useDataStore } from '@/store/dataStore.js';

export default {
  setup() {
    const user = ref({
      name: 'John Doe',
      email: 'john.doe@example.com',
      role: 'User  ',
      credits: 500,
      photo: ''
    });
    const showEditProfileModal = ref(false);
    const selectedFile = ref(null);
    const dataStore = useDataStore();

    const fetchUserProfile = async () => {
      user.value = dataStore.user;
      if(!user.value) await dataStore.fetchUserData();
      user.value = dataStore.user;
      if (!user.value.photo) user.value.photo = defaultPicture;
    };

    const openEditProfileModal = () => {
      showEditProfileModal.value = true;
    };

    const closeEditProfileModal = () => {
      showEditProfileModal.value = false;
    };

    const saveProfile = async () => {
      try {
        const formData = new FormData();
        formData.append('name', user.value.name);
        formData.append('email', user.value.email);
        if (selectedFile.value) {
          formData.append('photo', selectedFile.value);
        }

        const response = await apiClient.put('api/auth/profile', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        if (response.code === 200) {
          closeEditProfileModal();
          await dataStore.fetchUserData()        
        }
      } catch (error) {
        console.error(error);
      }
    };

    const onFileChange = (event) => {
      selectedFile.value = event.target.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        user.value.photo = e.target.result;
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
    };

    onMounted(() => {
      fetchUserProfile();
    });

    return {
      user,
      showEditProfileModal,
      selectedFile,
      fetchUserProfile,
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