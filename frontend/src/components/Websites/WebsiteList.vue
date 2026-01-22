<script setup>
import { useDashboardStore } from '@/stores/dashboard'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import WebsiteItem from '@/components/Websites/WebsiteItem.vue'
import SearchIcon from '@/components/icons/SearchIcon.vue'

const store = useDashboardStore()

const emit = defineEmits(['website-click'])

function handleWebsiteClick(website) {
  emit('website-click', website)
}
</script>

<template>
  <div class="bg-white rounded-2xl shadow-xl shadow-blue-900/5 p-8 border border-gray-100">
    <!-- Loading state -->
    <div v-if="store.isLoadingWebsites" class="flex items-center justify-center py-8">
      <LoadingSpinner size="xl" color="text-blue-500" />
      <span class="ml-3 text-gray-500">Loading websites...</span>
    </div>

    <!-- No client selected -->
    <div v-else-if="!store.selectedClient" class="text-center py-8 text-gray-500">
      <SearchIcon class="mx-auto text-gray-300 mb-4" />
      <p>Select a client to view their monitored websites</p>
    </div>

    <!-- Websites list -->
    <template v-else>
      <h2 class="text-lg font-semibold text-gray-800 mb-6">
        Monitored Websites for
        <span class="text-blue-500">{{ store.selectedClientEmail }}</span>:
      </h2>

      <!-- Empty state -->
      <div v-if="store.websites.length === 0" class="text-center py-8 text-gray-500">
        <p>No websites are being monitored for this client.</p>
      </div>

      <!-- Website list -->
      <ul v-else class="space-y-4">
        <WebsiteItem
          v-for="website in store.websites"
          :key="website.uuid"
          :website="website"
          @click="handleWebsiteClick"
        />
      </ul>
    </template>
  </div>
</template>
