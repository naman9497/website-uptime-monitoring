<script setup>
import Modal from '@/components/ui/Modal.vue'

defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  url: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['confirm', 'cancel'])

function handleConfirm() {
  emit('confirm')
}

function handleCancel() {
  emit('cancel')
}
</script>

<template>
  <Modal :is-open="isOpen" @close="handleCancel">
    <template #title>
      <p class="text-gray-700 text-lg leading-relaxed">
        You are about to visit <br>
        <span class="text-blue-500 underline font-medium break-all">{{ url }}</span> <br>
        Do you want to continue?
      </p>
    </template>

    <template #actions>
      <button
        @click="handleConfirm"
        class="w-full bg-[#2563eb] hover:bg-blue-700 text-white font-bold py-3 rounded-md uppercase text-sm tracking-widest transition-colors shadow-lg shadow-blue-500/30"
      >
        Continue
      </button>
      <button
        @click="handleCancel"
        class="w-full bg-[#e2e8f0] hover:bg-gray-300 text-gray-600 font-bold py-3 rounded-md uppercase text-sm tracking-widest transition-colors"
      >
        Cancel
      </button>
    </template>
  </Modal>
</template>
