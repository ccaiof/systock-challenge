<script setup>
import { ref } from 'vue'
import api from '@/services/axios'

const email = ref('')
const password = ref('')
const loading = ref(false)
const showPassword = ref(false)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')
const form = ref(null)

const emailRules = [
  (v) => !!v || 'Email é obrigatório',
  (v) => /.+@.+\..+/.test(v) || 'Email deve ser válido',
]

const passwordRules = [
  (v) => !!v || 'Senha é obrigatória',
  (v) => v.length >= 6 || 'Senha deve ter pelo menos 6 caracteres',
]

const showSnackbar = (message, color = 'success') => {
  snackbarMessage.value = message
  snackbarColor.value = color
  snackbar.value = true
}

const login = async () => {
  const { valid } = await form.value.validate()
  if (!valid) return

  loading.value = true
  try {
    await api.get('/sanctum/csrf-cookie')

    const response = await api.post('/login', {
      email: email.value,
      password: password.value,
    })

    showSnackbar('Login realizado com sucesso!', 'success')
    console.log('Login response:', response.data)
  } catch (error) {
    const message = error.response?.data?.message || 'Erro ao fazer login'
    showSnackbar(message, 'error')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="d-flex justify-center align-center" style="height: 100vh">
    <VCard class="w-100" style="max-width: 400px">
      <VForm ref="form" @submit.prevent="login" class="pa-6">
        <h2 class="text-h5 mb-6 text-center">Fazer Login</h2>

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
          label="Senha"
          v-model="password"
          :rules="passwordRules"
          :type="showPassword ? 'text' : 'password'"
          :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="showPassword = !showPassword"
          prepend-inner-icon="mdi-lock"
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
          {{ loading ? 'Entrando...' : 'Entrar' }}
        </VBtn>

        <div class="text-center mt-4">
          <router-link to="/register" class="text-decoration-none">
            Não tem conta? Cadastre-se aqui
          </router-link>
        </div>
      </VForm>
    </VCard>

    <VSnackbar v-model="snackbar" :color="snackbarColor" timeout="5000">
      {{ snackbarMessage }}
    </VSnackbar>
  </div>
</template>
