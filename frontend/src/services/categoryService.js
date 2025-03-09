import api from './api'

export const categoryService = {
  // Get all categories
  getCategories: async (params = {}) => {
    return await api.get('/api/categories', { params })
  },

  // Get active categories
  getActiveCategories: async () => {
    return await api.get('/api/categories', { params: { active_only: true } })
  },

  // Get category by ID
  getCategoryById: async (id) => {
    return await api.get(`/api/categories/${id}`)
  },

  // Create new category
  createCategory: async (categoryData) => {
    return await api.post('/api/categories', categoryData)
  },

  // Update category
  updateCategory: async (id, categoryData) => {
    return await api.put(`/api/categories/${id}`, categoryData)
  },

  // Delete category
  deleteCategory: async (id) => {
    return await api.delete(`/api/categories/${id}`)
  }
}