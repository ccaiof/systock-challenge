<script setup>
defineProps({
  drawer: {
    type: Boolean,
    required: true,
  },
})

defineEmits(['update:drawer'])

const items = [
  {
    title: 'Users',
    value: '/users',
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
    <v-list :items="items" />
  </v-navigation-drawer>
</template>
