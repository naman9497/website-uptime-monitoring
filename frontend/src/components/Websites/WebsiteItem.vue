<script setup>
defineProps({
  website: {
    type: Object,
    default: {},
    required: true
  }
})

const emit = defineEmits(['click'])

function getStatusColor(status) {
  switch (status) {
    case 'up':
      return 'text-green-500'
    case 'down':
      return 'text-red-500'
    default:
      return 'text-gray-400'
  }
}
</script>

<template>
  <li class="flex items-center justify-between group">
    <div class="flex items-center space-x-3">
      <span class="h-1.5 w-1.5 bg-gray-400 rounded-full"></span>
      <button
        @click="emit('click', website)"
        class="text-blue-600 hover:text-blue-800 underline font-medium transition-colors text-left"
      >
        {{ website.url }}
      </button>
    </div>
    <span :class="getStatusColor(website.current_status?.current_status)">
      <!-- Up status (checkmark) -->
      <svg
        v-if="website.current_status?.current_status === 'up'"
        class="w-5 h-5 fill-current"
        viewBox="0 0 20 20"
      >
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
      <!-- Down status (X) -->
      <svg
        v-else-if="website.current_status?.current_status === 'down'"
        class="w-5 h-5 fill-current"
        viewBox="0 0 20 20"
      >
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
      </svg>
      <!-- Unknown status (question mark) -->
      <svg
        v-else
        class="w-5 h-5 fill-current"
        viewBox="0 0 20 20"
      >
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
      </svg>
    </span>
  </li>
</template>
