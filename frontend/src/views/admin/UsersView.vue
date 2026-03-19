<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUsersStore } from '@/stores/users'

const router = useRouter()
const usersStore = useUsersStore()
const showDialog = ref(false)
const isEditMode = ref(false)
const formData = ref(initializeForm())
const selectedUserId = ref(null)

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

function initializeForm() {
  return {
    name: '',
    email: '',
    cpf: '',
    password: '',
  }
}

const openDialog = (user = null) => {
  if (user) {
    isEditMode.value = true
    selectedUserId.value = user.id
    formData.value = {
      name: user.name,
      email: user.email,
      cpf: user.cpf,
      password: '',
    }
  } else {
    isEditMode.value = false
    selectedUserId.value = null
    formData.value = initializeForm()
  }
  showDialog.value = true
}

const closeDialog = () => {
  showDialog.value = false
  formData.value = initializeForm()
  isEditMode.value = false
  selectedUserId.value = null
}

const saveUser = async () => {
  try {
    if (isEditMode.value) {
      await usersStore.updateUser(selectedUserId.value, formData.value)
    } else {
      await usersStore.createUser(formData.value)
    }
    closeDialog()
  } catch (error) {
    console.error('Erro ao salvar usuário:', error)
  }
}

const deleteUser = async (id) => {
  if (confirm('Tem certeza que deseja deletar este usuário?')) {
    try {
      await usersStore.deleteUser(id)
    } catch (error) {
      console.error('Erro ao deletar usuário:', error)
    }
  }
}

const viewUserProducts = (userId) => {
  router.push({ name: 'admin-user-products', params: { userId } })
}

onMounted(() => {
  usersStore.fetchUsers()
})
</script>

<template>
  <div>
    <v-card class="pa-6">
      <div class="d-flex justify-space-between align-center mb-6">
        <h1 class="text-h5">Gerenciamento de Usuários</h1>
        <v-btn color="primary" prepend-icon="mdi-plus" @click="openDialog()"> Novo Usuário </v-btn>
      </div>

      <v-progress-circular
        v-if="usersStore.loading"
        indeterminate
        color="primary"
        class="ma-auto"
      />

      <v-table v-else class="elevation-0">
        <thead>
          <tr>
            <th class="text-left">Nome</th>
            <th class="text-left">Email</th>
            <th class="text-left">CPF</th>
            <th class="text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in usersStore.users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.cpf || 'N/A' }}</td>
            <td class="text-center">
              <div class="d-flex justify-center gap-2">
                <v-btn
                  size="small"
                  icon="mdi-pencil"
                  variant="text"
                  color="primary"
                  @click="openDialog(user)"
                />
                <v-btn
                  size="small"
                  icon="mdi-package"
                  variant="text"
                  color="info"
                  @click="viewUserProducts(user.id)"
                  title="Ver produtos"
                />
                <v-btn
                  size="small"
                  icon="mdi-delete"
                  variant="text"
                  color="error"
                  @click="deleteUser(user.id)"
                />
              </div>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>

    <!-- Dialog de Criar/Editar Usuário -->
    <v-dialog v-model="showDialog" max-width="500">
      <v-card>
        <v-card-title>
          {{ isEditMode ? 'Editar Usuário' : 'Novo Usuário' }}
        </v-card-title>

        <v-card-text class="pt-6">
          <v-text-field
            v-model="formData.name"
            label="Nome"
            prepend-inner-icon="mdi-account"
            variant="outlined"
            class="mb-4"
          />

          <v-text-field
            v-model="formData.email"
            label="Email"
            type="email"
            prepend-inner-icon="mdi-email"
            variant="outlined"
            class="mb-4"
          />

          <v-text-field
            v-model="formData.cpf"
            label="CPF"
            prepend-inner-icon="mdi-card-account-details"
            variant="outlined"
            placeholder="000.000.000-00"
            class="mb-4"
          />

          <v-text-field
            v-model="formData.password"
            label="Senha"
            :placeholder="isEditMode ? 'Deixe em branco para manter' : 'Obrigatório'"
            type="password"
            prepend-inner-icon="mdi-lock"
            variant="outlined"
            class="mb-4"
          />
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn variant="text" @click="closeDialog">Cancelar</v-btn>
          <v-btn color="primary" variant="flat" @click="saveUser">
            {{ isEditMode ? 'Atualizar' : 'Criar' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
