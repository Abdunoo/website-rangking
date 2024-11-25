<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Rankings</h1>
            <button class="btn btn-primary">Update Rankings</button>
        </div>

        <div class="card">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Rank</th>
                        <th class="text-left py-3">Website</th>
                        <th class="text-left py-3">Score</th>
                        <th class="text-left py-3">Previous Rank</th>
                        <th class="text-left py-3">Change</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ranking in rankings" :key="ranking.id" class="border-b">
                        <td class="py-3">#{{ ranking.currentRank }}</td>
                        <td class="py-3">{{ ranking.website }}</td>
                        <td class="py-3">{{ ranking.score }}</td>
                        <td class="py-3">#{{ ranking.previousRank }}</td>
                        <td class="py-3">
                            <span :class="[
                                'px-2 py-1 rounded text-sm',
                                ranking.change === 'up' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]">
                                {{ ranking.change === 'up' ? '↑' : '↓' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const rankings = ref([
    {
        id: 1,
        website: 'Example.com',
        score: 95,
        previousRank: 2,
        currentRank: 1,
        change: 'up'
    },
    {
        id: 2,
        website: 'Sample.com',
        score: 88,
        previousRank: 1,
        currentRank: 2,
        change: 'down'
    },
]);
</script>
