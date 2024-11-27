<template>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h2 class="text-2xl font-bold">Change Password</h2>
            </div>

            <form @submit.prevent="handleChangePassword" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Current Password
                    </label>
                    <input v-model="currentPassword" type="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Enter current password" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <input v-model="newPassword" type="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Enter new password" />
                    <p v-if="passwordStrength" class="text-xs mt-1" :class="{
                        'text-red-500': passwordStrength === 'Weak',
                        'text-yellow-500': passwordStrength === 'Medium',
                        'text-green-500': passwordStrength === 'Strong'
                    }">
                        Password Strength: {{ passwordStrength }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <input v-model="confirmPassword" type="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Confirm new password" />
                </div>

                <div v-if="errorMessage" class="text-red-500 text-sm">
                    {{ errorMessage }}
                </div>

                <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg hover:bg-accent transition duration-300 
                   disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!isFormValid">
                    Change Password
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '@/helpers/axios'
import { useToast } from 'vue-toastification';

export default {
    name: 'ChangePassword',
    setup() {
        const currentPassword = ref('')
        const newPassword = ref('')
        const confirmPassword = ref('')
        const errorMessage = ref('')
        const router = useRouter()
        const toast = useToast();

        const passwordStrength = computed(() => {
            const password = newPassword.value
            if (password.length < 6) return 'Weak'

            const hasUppercase = /[A-Z]/.test(password)
            const hasLowercase = /[a-z]/.test(password)
            const hasNumber = /[0-9]/.test(password)
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password)

            const strengthCount =
                [hasUppercase, hasLowercase, hasNumber, hasSpecialChar]
                    .filter(Boolean).length

            if (strengthCount <= 2) return 'Weak'
            if (strengthCount === 3) return 'Medium'
            return 'Strong'
        })

        const isFormValid = computed(() => {
            return currentPassword.value &&
                newPassword.value &&
                confirmPassword.value &&
                newPassword.value === confirmPassword.value
        })

        const handleChangePassword = async () => {
            errorMessage.value = ''

            if (newPassword.value !== confirmPassword.value) {
                errorMessage.value = 'New passwords do not match'
                return
            }

            try {
                await apiClient.post('/api/change-password', {
                    current_password: currentPassword.value,
                    password: newPassword.value,
                    password_confirmation: confirmPassword.value
                })

                toast.success('Password changed successfully')

                router.push('/profile')
            } catch (error) {
                errorMessage.value = error.response?.data?.message || 'Failed to change password. Please try again.'
            }
        }

        return {
            currentPassword,
            newPassword,
            confirmPassword,
            errorMessage,
            passwordStrength,
            isFormValid,
            handleChangePassword
        }
    }
}
</script>