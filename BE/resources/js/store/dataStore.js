import { defineStore } from 'pinia';

export const useDataStore = defineStore('data', {
    state: () => ({
        loading: false,
    }),
    actions: {
        setLoading(value) {
            this.loading = value;
        },
    },
});
