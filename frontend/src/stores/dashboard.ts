import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import api from '@/services/api'
import type { Client } from '@/types/client'
import type { Website } from '@/types/website'

export const useDashboardStore = defineStore('dashboard', () => {
  const clients = ref<Client[]>([])
  const selectedClient = ref<Client | null>(null)
  const websites = ref<Website[]>([])
  const isLoadingClients = ref(false)
  const isLoadingWebsites = ref(false)
  const error = ref<string | null>(null)

  const selectedClientEmail = computed(() => selectedClient.value?.email ?? null)

  async function fetchClients() {
    isLoadingClients.value = true
    error.value = null
    try {
      const response = await api.get('/clients')
      clients.value = response.data.data
    } catch (e) {
      error.value = 'Failed to load clients'
      console.error('Error fetching clients:', e)
    } finally {
      isLoadingClients.value = false
    }
  }

  async function fetchWebsites(clientUuid: string) {
    isLoadingWebsites.value = true
    error.value = null
    try {
      const response = await api.get(`/clients/${clientUuid}/websites`)
      websites.value = response.data.data
    } catch (e) {
      error.value = 'Failed to load websites'
      console.error('Error fetching websites:', e)
    } finally {
      isLoadingWebsites.value = false
    }
  }

  function selectClient(client: Client | null) {
    selectedClient.value = client
    if (client) {
      fetchWebsites(client.uuid)
    } else {
      websites.value = []
    }
  }

  function clearSelection() {
    selectedClient.value = null
    websites.value = []
  }

  return {
    clients,
    selectedClient,
    selectedClientEmail,
    websites,
    isLoadingClients,
    isLoadingWebsites,
    error,
    fetchClients,
    fetchWebsites,
    selectClient,
    clearSelection
  }
})
