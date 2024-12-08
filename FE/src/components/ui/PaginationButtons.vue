<template>
    <div class="flex justify-between mt-4">
      <button
        class="px-4 py-2 bg-primary text-white rounded hover:bg-primary hover:bg-opacity-80 disabled:bg-gray-200 disabled:cursor-not-allowed"
        :disabled="currentPage === 1"
        @click="$emit('prev')"
      >
        Previous
      </button>
      <button
        class="px-4 py-2 bg-primary text-white rounded hover:bg-primary hover:bg-opacity-80 disabled:bg-gray-200 disabled:cursor-not-allowed"
        :disabled="currentPage === totalPages"
        @click="$emit('next')"
      >
        Next
      </button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'PaginationButtons',
    props: {
      currentPage: {
        type: Number,
        required: true,
      },
      totalPages: {
        type: Number,
        required: true,
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add any additional styles if necessary */
  </style>