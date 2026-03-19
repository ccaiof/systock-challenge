import { ref } from 'vue'
import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const loadingUser = ref(false)
  const token = ref(localStorage.getItem('auth_token'))

  const setToken = (value) => {
    token.value = value

    if (value) {
      localStorage.setItem('auth_token', value)
      api.defaults.headers.common.Authorization = `Bearer ${value}`
      return
    }

    localStorage.removeItem('auth_token')
    delete api.defaults.headers.common.Authorization
  }

  if (token.value) {
    api.defaults.headers.common.Authorization = `Bearer ${token.value}`
  }

  const fetchCurrentUser = async () => {
    if (!token.value) {
      user.value = null
      return null
    }

    loadingUser.value = true
    try {
      const response = await api.get('/api/v1/user')
      user.value = response.data
      return user.value
    } catch (error) {
      if (error.response?.status === 401) {
        setToken(null)
        user.value = null
      }

      throw error
    } finally {
      loadingUser.value = false
    }
  }

  const login = async (payload) => {
    const result = await api.post('/api/v1/login', payload)
    setToken(result.data.token)

    return fetchCurrentUser()
  }

  const register = async (payload) => {
    const result = await api.post('/api/v1/register', payload)
    setToken(result.data.token)

    return fetchCurrentUser()
  }

  const logout = async () => {
    try {
      if (token.value) {
        await api.post('/api/v1/logout')
      }
    } finally {
      setToken(null)
      user.value = null
    }
  }

  return {
    user,
    loadingUser,
    token,
    fetchCurrentUser,
    login,
    register,
    logout,
  }
})
