<script setup>
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useUsersStore } from '@/stores/users'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const usersStore = useUsersStore()
const authStore = useAuthStore()
const products = ref([])
const loading = ref(false)
const saving = ref(false)
const deletingId = ref(null)
const showCreateDialog = ref(false)
const isEditMode = ref(false)
const editingProductId = ref(null)
const userName = ref('')
const productForm = ref({
  nome: '',
  descricao: '',
  preco: '',
})

const loadUserProducts = async () => {
  loading.value = true
  try {
    const userId = route.params.userId
    products.value = await usersStore.fetchUserProducts(userId)

    userName.value = authStore.user?.name
  } catch (error) {
    console.error('Erro ao carregar produtos:', error)
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  router.push({ name: 'admin-users' })
}

const openCreateDialog = () => {
  isEditMode.value = false
  editingProductId.value = null
  productForm.value = {
    nome: '',
    descricao: '',
    preco: '',
  }
  showCreateDialog.value = true
}

const openEditDialog = (product) => {
  isEditMode.value = true
  editingProductId.value = product.id
  productForm.value = {
    nome: product.nome || '',
    descricao: product.descricao || '',
    preco: product.preco || '',
  }
  showCreateDialog.value = true
}

const closeCreateDialog = () => {
  showCreateDialog.value = false
  isEditMode.value = false
  editingProductId.value = null
}

const createProduct = async () => {
  const userId = route.params.userId
  saving.value = true
  try {
    if (isEditMode.value && editingProductId.value) {
      await usersStore.updateProduct(editingProductId.value, {
        nome: productForm.value.nome,
        descricao: productForm.value.descricao,
        preco: productForm.value.preco,
      })
    } else {
      await usersStore.createUserProduct(userId, {
        nome: productForm.value.nome,
        descricao: productForm.value.descricao,
        preco: productForm.value.preco,
      })
    }
    closeCreateDialog()
    await loadUserProducts()
  } catch (error) {
    console.error('Erro ao salvar produto:', error)
  } finally {
    saving.value = false
  }
}

const removeProduct = async (productId) => {
  const confirmed = confirm('Tem certeza que deseja excluir este produto?')
  if (!confirmed) return

  deletingId.value = productId
  try {
    await usersStore.deleteProduct(productId)
    products.value = products.value.filter((product) => product.id !== productId)
  } catch (error) {
    console.error('Erro ao excluir produto:', error)
  } finally {
    deletingId.value = null
  }
}

onMounted(loadUserProducts)
</script>

<template>
  <div>
    <v-card class="pa-6">
      <div class="d-flex justify-space-between align-center mb-6">
        <div>
          <v-btn variant="text" prepend-icon="mdi-arrow-left" @click="goBack" class="mb-2">
            Voltar
          </v-btn>
          <h1 class="text-h5">Produtos de {{ userName }}</h1>
        </div>

        <v-btn color="primary" prepend-icon="mdi-plus" @click="openCreateDialog">
          Criar Produto
        </v-btn>
      </div>

      <v-progress-circular v-if="loading" indeterminate color="primary" class="ma-auto" />

      <div v-else-if="products.length > 0">
        <v-table>
          <thead>
            <tr>
              <th class="text-left">Nome</th>
              <th class="text-left">Descrição</th>
              <th class="text-left">Preço</th>
              <th class="text-right">Ações</th>
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
                  @click="openEditDialog(product)"
                />
                <v-btn
                  icon="mdi-delete"
                  size="small"
                  variant="text"
                  color="error"
                  :loading="deletingId === product.id"
                  :disabled="deletingId === product.id"
                  @click="removeProduct(product.id)"
                />
              </td>
            </tr>
          </tbody>
        </v-table>
      </div>

      <div v-else class="text-center py-12">
        <p class="text-body-1">Nenhum produto encontrado para este usuário.</p>
      </div>
    </v-card>

    <v-dialog v-model="showCreateDialog" max-width="520">
      <v-card>
        <v-card-title>{{ isEditMode ? 'Editar Produto' : 'Novo Produto' }}</v-card-title>
        <v-card-text class="pt-6">
          <v-text-field
            v-model="productForm.nome"
            label="Nome"
            variant="outlined"
            prepend-inner-icon="mdi-package-variant"
            class="mb-4"
          />

          <v-textarea
            v-model="productForm.descricao"
            label="Descrição"
            variant="outlined"
            prepend-inner-icon="mdi-text"
            rows="3"
            class="mb-4"
          />

          <v-text-field
            v-model="productForm.preco"
            label="Preço"
            type="number"
            min="0"
            step="0.01"
            variant="outlined"
            prepend-inner-icon="mdi-currency-brl"
          />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn variant="text" @click="closeCreateDialog">Cancelar</v-btn>
          <v-btn color="primary" :loading="saving" :disabled="saving" @click="createProduct">
            {{ isEditMode ? 'Atualizar' : 'Salvar' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
