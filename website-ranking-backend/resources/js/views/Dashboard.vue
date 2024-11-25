<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div v-for="stat in stats" :key="stat.title" class="card">
        <h3 class="text-gray-500">{{ stat.title }}</h3>
        <p class="text-2xl font-bold mt-2">{{ stat.value }}</p>
      </div>
    </div>

    <div class="card">
      <h2 class="text-xl font-semibold mb-4">Ranking Trends</h2>
      <div class="h-[400px]">
        <Line :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

const chartData = {
  labels: ['January', 'February', 'March', 'April', 'May', 'June'],
  datasets: [
    {
      label: 'Website Rankings',
      data: [65, 59, 80, 81, 56, 55],
      borderColor: 'rgb(75, 192, 192)',
      tension: 0.1
    }
  ]
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
};

const stats = ref([
  { title: 'Total Websites', value: '156' },
  { title: 'Active Rankings', value: '89' },
  { title: 'Categories', value: '12' },
  { title: 'Monthly Visitors', value: '25.4K' },
]);
</script>

