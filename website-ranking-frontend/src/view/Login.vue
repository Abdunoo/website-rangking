<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Button from '@/components/ui/Button.vue'
import apiClient from '@/helpers/axios';

export default {
  components: {
    Button
  },
  emits: ['update-credits'],
  setup(props, { emit }) {
    const email = ref('')
    const password = ref('')
    const errorMessage = ref('')

    const router = useRouter()

    const handleLogin = async () => {
      try {
        if (!email.value || !password.value) {
          errorMessage.value = 'Please fill in all fields'
          return
        }

        // Direct API call instead of using a service
        const response = await apiClient.post('/api/login', {
          email: email.value,
          password: password.value
        })

        if (response.code === 200) {
          // Store token, user info, etc.
          localStorage.setItem('token', response.data.token)
          localStorage.setItem('_usr', JSON.stringify(response.data.user))
          emit('update-credits', response.data.user.credits);
          console.log('update-credits', response.data.user.credits);
          router.push('/')
        } else {
          errorMessage.value = 'Invalid credentials'
        }
      } catch (error) {
        errorMessage.value = 'Login failed. Please try again.'
      }
    }

    return {
      email,
      password,
      errorMessage,
      handleLogin
    }
  }
}
</script>

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