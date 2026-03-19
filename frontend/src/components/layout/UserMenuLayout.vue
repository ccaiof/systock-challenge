<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const userName = computed(() => authStore.user?.name || 'Usuário')
const userEmail = computed(() => authStore.user?.email || '')
const userInitial = computed(() => (authStore.user?.name || 'U').charAt(0).toUpperCase())

const handleLogout = async () => {
  try {
    await authStore.logout()
  } finally {
    await router.push({ name: 'index' })
  }
}
</script>

<template>
  <v-menu>
    <template #activator="{ props }">
      <v-btn v-bind="props" icon>
        <v-avatar color="primary" size="34">
          <span class="text-white">{{ userInitial }}</span>
        </v-avatar>
      </v-btn>
    </template>

    <v-list min-width="240">
      <v-list-item :title="userName" :subtitle="userEmail" />
      <v-divider />
      <v-list-item prepend-icon="mdi-logout" title="Sair" @click="handleLogout" />
    </v-list>
  </v-menu>
</template>
