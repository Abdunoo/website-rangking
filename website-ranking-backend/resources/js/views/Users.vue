<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">User Management</h1>
            <button class="btn btn-primary">Add User</button>
        </div>

        <div class="card">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Name</th>
                        <th class="text-left py-3">Email</th>
                        <th class="text-left py-3">Role</th>
                        <th class="text-left py-3">Status</th>
                        <th class="text-left py-3">Join Date</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-b">
                        <td class="py-3">{{ user.name }}</td>
                        <td class="py-3">{{ user.email }}</td>
                        <td class="py-3">
                            <span class="badge badge-success">{{ user.role }}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge badge-success">{{ user.status }}</span>
                        </td>
                        <td class="py-3">{{ user.joinDate }}</td>
                        <td class="py-3">
                            <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                            <button class="text-red-600 hover:text-red-800">Deactivate</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const users = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        role: 'Admin',
        status: 'Active',
        joinDate: '2024-01-15'
    },
    {
        id: 2,
        name: 'Jane Smith',
        email: 'jane@example.com',
        role: 'Moderator',
        status: 'Active',
        joinDate: '2024-02-01'
    },
]);
</script>
