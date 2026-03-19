<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

const user = computed(() => authStore.user)
const userName = computed(() => authStore.user?.name || 'Usuário')
const userEmail = computed(() => authStore.user?.email || '')
const userInitial = computed(() => (authStore.user?.name || 'U').charAt(0).toUpperCase())

const loadUser = async () => {
  isLoading.value = true
  try {
    await authStore.fetchCurrentUser()
  } catch {
    await router.push({ name: 'index' })
  } finally {
    isLoading.value = false
  }
}

const handleLogout = async () => {
  try {
    await authStore.logout()
  } finally {
    await router.push({ name: 'index' })
  }
}

onMounted(loadUser)
</script>

<template>
  <v-app>
    <v-navigation-drawer>
      <v-list>
        <v-list-item title="Menu"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar title="Dashboard">
      <template #append>
        <v-menu v-if="user">
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
    </v-app-bar>

    <v-main>
      <v-container>
        <router-view :user="user" :loading-user="isLoading || authStore.loadingUser" />
      </v-container>
    </v-main>
  </v-app>
</template>
