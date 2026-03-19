<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AppBar from '@/components/layout/AppBarLayout.vue'
import Sidebar from '@/components/layout/SidebarLayout.vue'

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)
const drawer = ref(true)

const user = computed(() => authStore.user)

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

onMounted(loadUser)
</script>

<template>
  <v-app>
    <AppBar :drawer="drawer" @update:drawer="drawer = $event" />
    <Sidebar :drawer="drawer" @update:drawer="drawer = $event" />

    <v-main>
      <v-container>
        <router-view :user="user" :loading-user="isLoading || authStore.loadingUser" />
      </v-container>
    </v-main>
  </v-app>
</template>
