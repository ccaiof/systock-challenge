<script setup>
defineProps({
  user: {
    type: Object,
    default: null,
  },
  loadingUser: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <v-card class="pa-6">
    <h1 class="text-h5 mb-4">Dashboard</h1>

    <v-progress-circular v-if="loadingUser" indeterminate color="primary" />

    <div v-else-if="user">
      <p class="text-body-1"><strong>Nome:</strong> {{ user.name }}</p>
      <p class="text-body-1"><strong>Email:</strong> {{ user.email }}</p>
      <p class="text-body-1"><strong>CPF:</strong> {{ user.cpf || 'Não informado' }}</p>
    </div>

    <p v-else class="text-body-1">Não foi possível carregar os dados do usuário.</p>
  </v-card>
</template>
