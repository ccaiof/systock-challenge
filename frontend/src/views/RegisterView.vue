<script setup>
import { ref, watch } from 'vue'
import api from '@/services/axios'

const name = ref('')
const email = ref('')
const cpf = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const loading = ref(false)
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')
const form = ref(null)

const formatCPF = (value) => {
  const cleanValue = value.replace(/\D/g, '')
  if (cleanValue.length <= 3) return cleanValue
  if (cleanValue.length <= 6) return `${cleanValue.slice(0, 3)}.${cleanValue.slice(3)}`
  if (cleanValue.length <= 9)
    return `${cleanValue.slice(0, 3)}.${cleanValue.slice(3, 6)}.${cleanValue.slice(6)}`
  return `${cleanValue.slice(0, 3)}.${cleanValue.slice(3, 6)}.${cleanValue.slice(6, 9)}-${cleanValue.slice(9, 11)}`
}

watch(cpf, (newValue) => {
  cpf.value = formatCPF(newValue)
})

const nameRules = [
  (v) => !!v || 'Nome é obrigatório',
  (v) => v.length >= 3 || 'Nome deve ter pelo menos 3 caracteres',
]

const emailRules = [
  (v) => !!v || 'Email é obrigatório',
  (v) => /.+@.+\..+/.test(v) || 'Email deve ser válido',
]

const cpfRules = [
  (v) => !!v || 'CPF é obrigatório',
  (v) => /^\d{11}$/.test(v?.replace(/\D/g, '')) || 'CPF deve ter 11 dígitos',
]

const passwordRules = [
  (v) => !!v || 'Senha é obrigatória',
  (v) => v.length >= 6 || 'Senha deve ter pelo menos 6 caracteres',
]

const passwordConfirmationRules = [
  (v) => !!v || 'Confirmação de senha é obrigatória',
  (v) => v === password.value || 'As senhas devem ser iguais',
]

const showSnackbar = (message, color = 'success') => {
  snackbarMessage.value = message
  snackbarColor.value = color
  snackbar.value = true
}

const register = async () => {
  const { valid } = await form.value.validate()
  if (!valid) return

  loading.value = true
  try {
    await api.get('/sanctum/csrf-cookie')

    const response = await api.post('/register', {
      name: name.value,
      email: email.value,
      cpf: cpf.value.replace(/\D/g, ''),
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    })

    showSnackbar('Cadastro realizado com sucesso!', 'success')
    console.log('Register response:', response.data)

    // Limpar form
    name.value = ''
    email.value = ''
    cpf.value = ''
    password.value = ''
    passwordConfirmation.value = ''
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao fazer cadastro'
    showSnackbar(message, 'error')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="d-flex justify-center align-center" style="height: 100vh">
    <VCard class="w-100" style="max-width: 450px">
      <VForm ref="form" @submit.prevent="register" class="pa-6">
        <h2 class="text-h5 mb-6 text-center">Criar Conta</h2>

        <VTextField
          label="Nome Completo"
          v-model="name"
          :rules="nameRules"
          prepend-inner-icon="mdi-account"
          variant="outlined"
          class="mb-4"
          :disabled="loading"
        />

        <VTextField
          label="Email"
          v-model="email"
          :rules="emailRules"
          type="email"
          prepend-inner-icon="mdi-email"
          variant="outlined"
          class="mb-4"
          :disabled="loading"
        />

        <VTextField
          label="CPF"
          v-model="cpf"
          :rules="cpfRules"
          prepend-inner-icon="mdi-card-account-details"
          variant="outlined"
          placeholder="000.000.000-00"
          maxlength="14"
          class="mb-4"
          :disabled="loading"
        />

        <VTextField
          label="Senha"
          v-model="password"
          :rules="passwordRules"
          :type="showPassword ? 'text' : 'password'"
          :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="showPassword = !showPassword"
          prepend-inner-icon="mdi-lock"
          variant="outlined"
          class="mb-4"
          :disabled="loading"
        />

        <VTextField
          label="Confirmar Senha"
          v-model="passwordConfirmation"
          :rules="passwordConfirmationRules"
          :type="showPasswordConfirmation ? 'text' : 'password'"
          :append-inner-icon="showPasswordConfirmation ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="showPasswordConfirmation = !showPasswordConfirmation"
          prepend-inner-icon="mdi-lock-check"
          variant="outlined"
          class="mb-6"
          :disabled="loading"
        />

        <VBtn
          type="submit"
          color="primary"
          size="large"
          block
          :loading="loading"
          :disabled="loading"
        >
          {{ loading ? 'Cadastrando...' : 'Cadastrar' }}
        </VBtn>

        <div class="text-center mt-4">
          <router-link to="/" class="text-decoration-none">
            Já tem conta? Faça login aqui
          </router-link>
        </div>
      </VForm>
    </VCard>

    <VSnackbar v-model="snackbar" :color="snackbarColor" timeout="5000">
      {{ snackbarMessage }}
    </VSnackbar>
  </div>
</template>
