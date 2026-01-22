<script setup>
import CheckCircleIcon from '@/components/icons/CheckCircleIcon.vue'
import XCircleIcon from '@/components/icons/XCircleIcon.vue'
import QuestionCircleIcon from '@/components/icons/QuestionCircleIcon.vue'

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
    <span :class="getStatusColor(website.current_status && website.current_status.current_status)">
      <CheckCircleIcon v-if="website.current_status && website.current_status.current_status === 'up'" />
      <XCircleIcon v-else-if="website.current_status && website.current_status.current_status === 'down'" />
      <QuestionCircleIcon v-else />
    </span>
  </li>
</template>
