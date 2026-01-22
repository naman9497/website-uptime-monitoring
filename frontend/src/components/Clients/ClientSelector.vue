<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import Dropdown from '@/components/ui/Dropdown.vue'

const store = useDashboardStore()
const searchQuery = ref('')
const isDropdownOpen = ref(false)

const filteredClients = computed(() => {
  if (!searchQuery.value) {
    return store.clients
  }
  const query = searchQuery.value.toLowerCase()
  return store.clients.filter(client =>
    client.email.toLowerCase().includes(query)
  )
})

function selectClient(client) {
  store.selectClient(client)
  searchQuery.value = client.email
  isDropdownOpen.value = false
}

function handleFocus() {
  isDropdownOpen.value = true
}

function handleInput() {
  isDropdownOpen.value = true
  if (store.selectedClient && searchQuery.value !== store.selectedClient.email) {
    store.clearSelection()
  }
}

function closeDropdown() {
  isDropdownOpen.value = false
}

onMounted(() => {
  store.fetchClients()
})
</script>

<template>
  <div class="mb-8">
    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 pl-1">
      Select Client Email
    </label>
    <Dropdown
      :is-open="isDropdownOpen && !store.isLoadingClients && (filteredClients.length > 0 || (searchQuery && filteredClients.length === 0))"
      @click-outside="closeDropdown"
      class="group"
    >
      <template #trigger>
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <!-- Loading spinner -->
          <LoadingSpinner v-if="store.isLoadingClients" />
          <!-- Search icon -->
          <svg v-else class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input
          type="text"
          v-model="searchQuery"
          @focus="handleFocus"
          @input="handleInput"
          :placeholder="store.isLoadingClients ? 'Loading clients...' : 'Choose an email...'"
          :disabled="store.isLoadingClients"
          autocomplete="off"
          class="w-full bg-white border border-gray-200 rounded-lg py-4 pl-12 pr-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-gray-600 disabled:bg-gray-50 disabled:cursor-not-allowed"
        >
      </template>

      <!-- Client list -->
      <template v-if="filteredClients.length > 0">
        <button
          v-for="client in filteredClients"
          :key="client.uuid"
          @click="selectClient(client)"
          class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors text-gray-700 border-b border-gray-100 last:border-b-0"
        >
          {{ client.email }}
        </button>
      </template>

      <!-- No results -->
      <div
        v-else-if="searchQuery"
        class="p-4 text-gray-500 text-center"
      >
        No clients found
      </div>
    </Dropdown>

    <!-- Error state -->
    <div v-if="store.error" class="mt-3 text-red-500 text-sm pl-1">
      {{ store.error }}
    </div>
  </div>
</template>
