<template>
  <div class="p-4 space-y-3 hover:bg-gray-50 transition duration-200">
    <div class="flex justify-between items-center">
      <div class="flex items-center gap-2">
        <span class="font-medium">#{{ website.rank }}</span>
        <span 
          :class="[
            'text-sm',
            website.previous_rank >= website.rank ? 'text-primary' : 'text-danger'
          ]"
        >
          {{ getRankChange(website.rank, website.previous_rank) }}
        </span>
      </div>
      <span class="px-2 py-1 text-xs rounded-full bg-primary/10 text-primary">
        {{ website.category }}
      </span>
    </div>
    
    <RouterLink 
      :to="`/${website.name}`"
      class="block text-primary hover:underline font-medium"
    >
      {{ website.name }}
    </RouterLink>
    
    <div class="text-sm text-gray-600">
      {{ website.website }}
    </div>
  </div>
</template>

<script>

export default {
  name: 'WebsiteCard',
  props: {
    website: {
      type: Object,
      required: true
    }
  },
  methods: {
    getRankChange(current, previous) {
      const diff = previous - current;
      if (diff >= 0) return `↑${diff}`
      return `↓${diff}`;
    }
  }
}
</script>