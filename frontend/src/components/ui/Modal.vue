<script setup>
defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
})

const emit = defineEmits(['close'])

function handleBackdropClick() {
  emit('close')
}
</script>

<template>
  <Teleport to="body">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm flex items-center justify-center p-4 z-50"
      @click.self="handleBackdropClick"
    >
      <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-8 border border-gray-100">
        <div v-if="$slots.title" class="text-center mb-8">
          <slot name="title" />
        </div>

        <slot />

        <div v-if="$slots.actions" class="flex flex-col space-y-3 mt-8">
          <slot name="actions" />
        </div>
      </div>
    </div>
  </Teleport>
</template>
