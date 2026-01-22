<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['clickOutside'])

const dropdownRef = ref(null)

function handleClickOutside(event) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    emit('clickOutside')
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

</script>

<template>
  <div ref="dropdownRef" class="relative">
    <slot name="trigger" />
    <div
      v-if="isOpen"
      class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto"
    >
      <slot />
    </div>
  </div>
</template>
