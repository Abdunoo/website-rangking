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

<script>
import { onMounted, ref } from 'vue';
import { Line } from 'vue-chartjs';
import apiClient from '../helpers/axios';
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
import { useDataStore } from '../store/dataStore';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

export default {
    components: { Line },
    setup() {
        const dataStore = useDataStore();
        // Reactive chart data and options
        const chartData = ref({
            labels: [],
            data2: [],
            datasets: [
                {
                    label: 'Website Rankings',
                    data: [],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4
                }
            ]
        });

        const chartOptions = ref({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Website Rankings Over Time'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const index = context.dataIndex;
                            const count = context.raw;
                            const websiteName = chartData.value.data2[index];
                            return `${websiteName}: ${count} searches`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Search Count'
                    },
                    beginAtZero: true
                }
            }
        });

        const stats = ref([
            { title: 'Total Websites', value: '0' },
            { title: 'Active Rankings', value: '0' },
            { title: 'Categories', value: '0' },
            { title: 'Monthly Visitors', value: '0' }
        ]);

        const fetchDataState = async () => {
            dataStore.setLoading(true);
            try {
                const statsResponse = await apiClient.get('/api/admin/stats');
                stats.value = statsResponse.data;
            } catch (error) {
                console.error('Error fetching stats data:', error);
            }
            dataStore.setLoading(false);
        };

        const fetchData = async () => {
            dataStore.setLoading(true);
            try {
                const response = await apiClient.get('/api/admin/website-rankings');
                const rankingsData = response.data;

                if (rankingsData.length > 0) {
                    const labels = rankingsData.map(item => `${item.month} ${item.year}`);
                    const data = rankingsData.map(item => item.count);
                    const data2 = rankingsData.map(item => item.websites.domain);

                    chartData.value = {
                        labels: labels,
                        data2: data2,
                        datasets: [
                            {
                                label: 'Website Rankings',
                                data: data,
                                borderColor: 'rgb(75, 192, 192)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.4
                            }
                        ]
                    };
                } else {
                    console.warn('No rankings data available.');
                }
            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
            dataStore.setLoading(false);
        };

        onMounted(() => {
            fetchDataState();
            fetchData();
        });

        return {
            chartData,
            chartOptions,
            stats
        };
    }
};
</script>
