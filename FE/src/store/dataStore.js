import { defineStore } from 'pinia';
import defaultPicture from '@/assets/images/photo_profile.webp';
import apiClient from '@/helpers/axios';


export const useDataStore = defineStore('data', {
  state: () => ({
    user: {
      name: 'John Doe',
      email: 'john.doe@example.com',
      role: 'user',
      credits: 500,
      photo: ''
    }, 
    profilePhoto: defaultPicture,
    credits: 0, 
    searchQuery: '',
    selectedCategory: '',
    defaultPicture,
    loading: false,
  }),
  actions: {
    async fetchUserData() {
      try {
        const response = await apiClient.get('api/auth/me');
        this.user = response.data;
        this.profilePhoto = this.user.photo ?? defaultPicture;
        this.credits = this.user.credits || 0;
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    },
    updateCredits(newCredits) {
      this.credits = newCredits;
    },
    increaseCredit(value) {
      this.credits += value;
    },
    decreaseCredit(value) {
      this.credits -= value;
    },
    updateSearchQuery(query, selectedCategory) {
      this.selectedCategory = selectedCategory;
      // if (query.length < 2) return;
      this.searchQuery = query;
    },
    removeCredits() {
      this.credits = 0;
    },
    removePhotoProfile() {
      this.profilePhoto = defaultPicture;
    },
    updatePhotoProfile(value) {
      this.profilePhoto = value;
    },
    setLoading(value) {
      this.loading = value;
    },
  },
});
