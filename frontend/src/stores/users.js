import { ref } from 'vue'
import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useUsersStore = defineStore('users', () => {
  const users = ref([])
  const loading = ref(false)
  const selectedUser = ref(null)

  const fetchUsers = async () => {
    loading.value = true
    try {
      const response = await api.get('/api/v1/users')
      users.value = response.data.data || response.data
    } finally {
      loading.value = false
    }
  }

  const createUser = async (payload) => {
    const response = await api.post('/api/v1/users', payload)
    users.value.push(response.data.data || response.data)
    return response.data
  }

  const updateUser = async (id, payload) => {
    const response = await api.put(`/api/v1/users/${id}`, payload)
    const index = users.value.findIndex((u) => u.id === id)
    if (index !== -1) {
      users.value[index] = response.data.data || response.data
    }
    return response.data
  }

  const deleteUser = async (id) => {
    await api.delete(`/api/v1/users/${id}`)
    users.value = users.value.filter((u) => u.id !== id)
  }

  const fetchUserProducts = async (userId) => {
    const response = await api.get(`/api/v1/users/${userId}/products`)
    return response.data.data || response.data
  }

  const createUserProduct = async (userId, payload) => {
    const response = await api.post(`/api/v1/users/${userId}/products`, payload)
    return response.data.data || response.data
  }

  const updateProduct = async (productId, payload) => {
    const response = await api.put(`/api/v1/products/${productId}`, payload)
    return response.data.data || response.data
  }

  const deleteProduct = async (productId) => {
    await api.delete(`/api/v1/products/${productId}`)
  }

  return {
    users,
    loading,
    selectedUser,
    fetchUsers,
    createUser,
    updateUser,
    deleteUser,
    fetchUserProducts,
    createUserProduct,
    updateProduct,
    deleteProduct,
  }
})
