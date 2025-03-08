import api from './api'

export const categoryService = {
  // Tüm kategorileri getir
  getCategories: async () => {
    return await api.get('/categories')
  },

  // Kategori detayını getir
  getCategoryById: async (id) => {
    return await api.get(`/categories/${id}`)
  },

  // Yeni kategori oluştur
  createCategory: async (categoryData) => {
    return await api.post('/categories', categoryData)
  },

  // Kategori güncelle
  updateCategory: async (id, categoryData) => {
    return await api.put(`/categories/${id}`, categoryData)
  },

  // Kategori sil
  deleteCategory: async (id) => {
    return await api.delete(`/categories/${id}`)
  },

  // Aktif kategorileri getir
  getActiveCategories: async () => {
    return await api.get('/categories/active')
  },

  // Kategori slug'ına göre detay getir
  getCategoryBySlug: async (slug) => {
    return await api.get(`/categories/slug/${slug}`)
  }
}