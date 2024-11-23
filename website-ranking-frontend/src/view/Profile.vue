<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mx-auto bg-white shadow-md rounded-lg overflow-hidden">
      <div class="bg-primary text-white p-6">
        <h2 class="text-2xl font-bold">User  Profile</h2>
      </div>
      
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
          <button 
            @click="openEditProfileModal"
            class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors"
          >
            Edit Profile
          </button>
          
          <button 
            @click="redirectToChangePassword"
            class="bg-secondary text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors"
          >
            Change Password
          </button>

          <button 
            @click="logout"
            class="bg-danger text-white px-4 py-2 rounded hover:bg-opacity-80 transition-colors"
          >
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
          <input 
            type="text" 
            v-model="user.name" 
            class="w-full px-3 py-2 border rounded"
          >
        </div>
        <div class="mb-4">
          <label class="block mb-2">Email</label>
          <input 
            type="email" 
            v-model="user.email" 
            class="w-full px-3 py-2 border rounded"
          >
        </div>
        <div class="flex justify-between">
          <button 
            @click="saveProfile"
            class="bg-primary text-white px-4 py-2 rounded"
          >
            Save Changes
          </button>
          <button 
            @click="closeEditProfileModal"
            class="bg-secondary text-white px-4 py-2 rounded"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import router from '@/router';

export default {
  data() {
    return {
      user: {
        name: 'John Doe',
        email: 'john.doe@example.com',
        role: 'User ',
        credits: 500
      },
      showEditProfileModal: false
    }
  },
  methods: {
    openEditProfileModal() {
      this.showEditProfileModal = true;
    },
    closeEditProfileModal() {
      this.showEditProfileModal = false;
    },
    saveProfile() {
      console.log('Profile saved:', this.user);
      this.closeEditProfileModal();
    },
    redirectToChangePassword() {
      router.push('/change-password'); 
    },
    logout() {
      console.log('Logging out');
      localStorage.removeItem('token');
      this.$router.push('/login')
    }
  },
  mounted() {
    // Fetch user data from API
    // this.fetchUser Profile()
  }
}
</script>

<style scoped>
/* Additional custom styles if needed */
</style>