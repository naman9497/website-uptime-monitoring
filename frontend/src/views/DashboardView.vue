<script setup lang="ts">
import { ref } from 'vue'
import ClientSelector from '@/components/Clients/ClientSelector.vue'
import WebsiteList from '@/components/Websites/WebsiteList.vue'
import ConfirmationModal from '@/components/ConfirmationModal.vue'
import type { Website } from '@/types/website'

const isModalOpen = ref(false)
const selectedWebsiteUrl = ref('')

function handleWebsiteClick(website: Website) {
  selectedWebsiteUrl.value = website.url
  isModalOpen.value = true
}

function handleConfirm() {
  window.open(selectedWebsiteUrl.value, '_blank', 'noopener,noreferrer')
  isModalOpen.value = false
}

function handleCancel() {
  isModalOpen.value = false
}
</script>

<template>
  <div class="w-full max-w-3xl">
    <header class="text-left mb-10 pl-2">
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight leading-tight">WEBSITE UPTIME MONITOR</h1>
      <p class="text-gray-500 text-sm font-medium mt-1">Client Dashboard</p>
    </header>

    <ClientSelector />

    <WebsiteList @website-click="handleWebsiteClick" />

    <ConfirmationModal
      :is-open="isModalOpen"
      :url="selectedWebsiteUrl"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </div>
</template>
