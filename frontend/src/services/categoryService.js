import api from './api'

export const categoryService = {
  // Get all categories
  getCategories: async (params = {}) => {
    return await api.get('/categories', { params })
  },

  // Get active categories
  getActiveCategories: async () => {
    return await api.get('/categories', { params: { active_only: true } })
  },

  // Get category by ID
  getCategoryById: async (id) => {
    return await api.get(`/categories/${id}`)
  },

  // Create new category
  createCategory: async (categoryData) => {
    return await api.post('/categories', categoryData)
  },

  // Update category
  updateCategory: async (id, categoryData) => {
    return await api.put(`/categories/${id}`, categoryData)
  },

  // Delete category
  deleteCategory: async (id) => {
    return await api.delete(`/categories/${id}`)
  }
}