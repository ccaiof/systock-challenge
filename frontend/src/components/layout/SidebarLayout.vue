<script setup>
import { useRoute } from 'vue-router'

defineProps({
  drawer: {
    type: Boolean,
    required: true,
  },
})

defineEmits(['update:drawer'])
const route = useRoute()

const items = [
  {
    title: 'Dashboard',
    to: '/admin/dashboard',
    props: {
      prependIcon: 'mdi-home',
    },
  },
  {
    title: 'Usuários',
    to: '/admin/users',
    props: {
      prependIcon: 'mdi-account-group',
    },
  },
]
</script>

<template>
  <v-navigation-drawer
    :model-value="drawer"
    @update:model-value="$emit('update:drawer', $event)"
    :location="$vuetify.display.mobile ? 'bottom' : undefined"
  >
    <v-list nav density="comfortable">
      <v-list-item
        v-for="item in items"
        :key="item.to"
        :to="item.to"
        :title="item.title"
        :prepend-icon="item.props.prependIcon"
        :active="route.path === item.to"
        rounded="lg"
      />
    </v-list>
  </v-navigation-drawer>
</template>
