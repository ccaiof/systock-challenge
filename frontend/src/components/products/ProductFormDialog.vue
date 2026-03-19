<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  isEditMode: {
    type: Boolean,
    default: false,
  },
  saving: {
    type: Boolean,
    default: false,
  },
  form: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['update:modelValue', 'update:form', 'save'])

const dialogModel = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const updateField = (field, value) => {
  emit('update:form', {
    ...props.form,
    [field]: value,
  })
}
</script>

<template>
  <v-dialog v-model="dialogModel" max-width="520">
    <v-card>
      <v-card-title>{{ isEditMode ? 'Editar Produto' : 'Novo Produto' }}</v-card-title>
      <v-card-text class="pt-6">
        <v-text-field
          :model-value="form.nome"
          label="Nome"
          variant="outlined"
          prepend-inner-icon="mdi-package-variant"
          class="mb-4"
          @update:model-value="updateField('nome', $event)"
        />

        <v-textarea
          :model-value="form.descricao"
          label="Descricao"
          variant="outlined"
          prepend-inner-icon="mdi-text"
          rows="3"
          class="mb-4"
          @update:model-value="updateField('descricao', $event)"
        />

        <v-text-field
          :model-value="form.preco"
          label="Preco"
          type="number"
          min="0"
          step="0.01"
          variant="outlined"
          prepend-inner-icon="mdi-currency-brl"
          @update:model-value="updateField('preco', $event)"
        />
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn variant="text" @click="dialogModel = false">Cancelar</v-btn>
        <v-btn color="primary" :loading="saving" :disabled="saving" @click="$emit('save')">
          {{ isEditMode ? 'Atualizar' : 'Salvar' }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
