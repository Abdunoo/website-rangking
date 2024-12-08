<template>
  <button
    :class="[
      'px-4 py-2 rounded-lg transition duration-200',
      variant === 'secondary' 
        ? 'border border-primary text-primary hover:bg-primary/10' 
        : 'bg-primary text-white hover:bg-accent',
      fullWidth ? 'w-full' : ''
    ]"
  >
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    variant: {
      type: String,
      validator: (value) => ['primary', 'secondary'].includes(value),
      default: 'primary'
    },
    fullWidth: {
      type: Boolean,
      default: false
    }
  }
}
</script>