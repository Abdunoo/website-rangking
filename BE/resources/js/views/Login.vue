<template>
    <div class="max-w-md flex flex-col items-center mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        <form @submit.prevent="handleLogin" class="space-y-4 flex flex-col items-center">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input v-model="email" type="email" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <input v-model="password" type="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>

            <div v-if="errorMessage" class="text-red-500 text-sm">
                {{ errorMessage }}
            </div>

            <button class="btn btn-primary" type="submit" fullWidth>Login</button>
        </form>
    </div>
</template>

<script>
import { reactive, ref, toRefs } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '../helpers/axios';
import { useDataStore } from '../store/dataStore';
import { useToast } from 'vue-toastification';
// import Button from '@/components/ui/Button.vue'
// import apiClient from '@/helpers/axios';
// import { useDataStore } from '@/store/dataStore.js';

export default {
    components: {
        // Button
    },
    setup() {
        const state = reactive({
            email: '',
            password: '',
            errorMessage: ''
        })

        const router = useRouter()
        const dataStore = useDataStore();
        const toast = useToast();

        const handleLogin = async () => {
            dataStore.setLoading(true);
            try {
                if (!state.email || !state.password) {
                    errorMessage.value = 'Please fill in all fields'
                    return
                }
                const response = await apiClient.post('/api/admin/login', {
                    email: state.email,
                    password: state.password
                })

                if (response.code === 200) {
                    toast.success('Login Success')
                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('_usr', JSON.stringify(response.data.user));
                    router.push('/dashboard')
                } else {
                    state.errorMessage = 'Invalid credentials'
                }
            } catch (error) {
                toast.error(error.response.data.message)
                state.errorMessage = 'Login failed. Please try again.'
            }
            dataStore.setLoading(false);
        }

        return {
            ...toRefs(state),
            handleLogin
        }
    }
}
</script>
