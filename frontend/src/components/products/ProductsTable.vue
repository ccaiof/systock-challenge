<script setup>
defineProps({
  products: {
    type: Array,
    default: () => [],
  },
  deletingId: {
    type: [Number, String, null],
    default: null,
  },
})

defineEmits(['edit', 'delete'])
</script>

<template>
  <v-table>
    <thead>
      <tr>
        <th class="text-left">Nome</th>
        <th class="text-left">Descricao</th>
        <th class="text-left">Preco</th>
        <th class="text-right">Acoes</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in products" :key="product.id">
        <td>{{ product.nome }}</td>
        <td>{{ product.descricao }}</td>
        <td>R$ {{ parseFloat(product.preco).toFixed(2) }}</td>
        <td class="text-right">
          <v-btn
            icon="mdi-pencil"
            size="small"
            variant="text"
            color="primary"
            @click="$emit('edit', product)"
          />
          <v-btn
            icon="mdi-delete"
            size="small"
            variant="text"
            color="error"
            :loading="deletingId === product.id"
            :disabled="deletingId === product.id"
            @click="$emit('delete', product.id)"
          />
        </td>
      </tr>
    </tbody>
  </v-table>
</template>
