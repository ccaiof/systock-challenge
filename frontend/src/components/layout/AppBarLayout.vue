<script setup>
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import UserMenu from './UserMenuLayout.vue'

defineProps({
  drawer: {
    type: Boolean,
    required: true,
  },
})

defineEmits(['update:drawer'])

const authStore = useAuthStore()
const user = computed(() => authStore.user)
</script>

<template>
  <v-app-bar>
    <VAppBarNavIcon variant="text" @click="$emit('update:drawer', !drawer)" />
    <v-toolbar-title>Systock Challenge</v-toolbar-title>
    <template #append>
      <UserMenu v-if="user" />
    </template>
  </v-app-bar>
</template>
