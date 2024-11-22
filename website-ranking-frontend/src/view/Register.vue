<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Button from '@/components/ui/Button.vue'
import axios from 'axios'
import apiClient from '@/helpers/axios';

export default {
  components: {
    Button
  },
  setup() {
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const confirmPassword = ref('')
    const errorMessage = ref('')

    const router = useRouter()

    const handleRegister = async () => {
      try {
        if (!name.value || !email.value || !password.value) {
          errorMessage.value = 'Please fill in all fields'
          return
        }

        if (password.value !== confirmPassword.value) {
          errorMessage.value = 'Passwords do not match'
          return
        }

        // Direct API call for registration
        await apiClient.post('/api/register', {
          name: name.value,
          email: email.value,
          password: password.value
        })

        router.push('/login')
      } catch (error) {
        errorMessage.value = 'Registration failed. Please try again.'
      }
    }

    return {
      name,
      email,
      password,
      confirmPassword,
      errorMessage,
      handleRegister
    }
  }
}
</script>

<template>
  <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
    
    <form @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Name
        </label>
        <input 
          v-model="name"
          type="text" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>
      
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
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Confirm Password
        </label>
        <input 
          v-model="confirmPassword"
          type="password" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>
      
      <div v-if="errorMessage" class="text-red-500 text-sm">
        {{ errorMessage }}
      </div>
      
      <Button type="submit" fullWidth>Register</Button>
    </form>
    
    <div class="mt-4 text-center">
      <p class="text-sm text-gray-600">
        Already have an account? 
        <RouterLink to="/login" class="text-primary hover:underline">
          Login
        </RouterLink>
      </p>
    </div>
  </div>
</template>