import api from './api'

export const tourService = {
  // Get list of tours
  getTours: async (params = {}) => {
    return await api.get('/tours', { params })
  },

  // Get a single tour detail
  getTourById: async (id) => {
    return await api.get(`/tours/${id}`)
  },

  // Create a new tour
  createTour: async (tourData) => {
    return await api.post('/tours', tourData)
  },

  // Update tour
  updateTour: async (id, tourData) => {
    return await api.put(`/tours/${id}`, tourData)
  },

  // Delete tour
  deleteTour: async (id) => {
    return await api.delete(`/tours/${id}`)
  },

  // Search tours
  searchTours: async (query, category) => {
    return await api.get('/tours/search', {
      params: { q: query, category }
    })
  },

  // Get featured tours
  getFeaturedTours: async () => {
    return await api.get('/tours/featured')
  },

  // Get tours by category
  getToursByCategory: async (categorySlug) => {
    return await api.get(`/tours/category/${categorySlug}`)
  }
}