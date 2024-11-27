<template>
  <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    
    <form @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Email
        </label>
        <input 
          v-model="email"
          type="email" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Password
        </label>
        <input 
          v-model="password"
          type="password" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>
      
      <div v-if="errorMessage" class="text-red-500 text-sm">
        {{ errorMessage }}
      </div>
      
      <Button type="submit" fullWidth>Login</Button>
    </form>
    
    <div class="mt-4 text-center">
      <p class="text-sm text-gray-600">
        Don't have an account? 
        <RouterLink to="/register" class="text-primary hover:underline">
          Register
        </RouterLink>
      </p>
    </div>
  </div>
</template>

<script>
import { reactive, ref, toRefs } from 'vue'
import { useRouter } from 'vue-router'
import Button from '@/components/ui/Button.vue'
import apiClient from '@/helpers/axios';
import { useDataStore } from '@/store/dataStore.js';

export default {
  components: {
    Button
  },
  setup() {
    const state = reactive({
      email: '',
      password: '',
      errorMessage: ''
    })

    const router = useRouter()
    const dataStore = useDataStore();

    const handleLogin = async () => {
      try {
        if (!state.email || !state.password) {
          errorMessage.value = 'Please fill in all fields'
          return
        }

        const response = await apiClient.post('/api/login', {
          email: state.email,
          password: state.password
        })

        if (response.code === 200) {
          dataStore.updateCredits(response.data.user.credits);
          if (response.data.user.photo) dataStore.updatePhotoProfile(response.data.user.photo);
          localStorage.setItem('token', response.data.token);
          localStorage.setItem('_usr', JSON.stringify(response.data.user));
          router.push('/')
        } else {
          state.errorMessage = 'Invalid credentials'
        }
      } catch (error) {
        state.errorMessage = 'Login failed. Please try again.'
      }
    }

    return {
      ...toRefs(state),
      handleLogin
    }
  }
}
</script>

