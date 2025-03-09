import api from './api'

export const tourService = {
  // Get list of tours
  getTours: async (params = {}) => {
    return await api.get('/api/tours', { params })
  },

  // Get a single tour detail
  getTourById: async (id) => {
    return await api.get(`/api/tours/${id}`)
  },

  // Create a new tour
  createTour: async (tourData) => {
    return await api.post('/api/tours', tourData)
  },

  // Update tour
  updateTour: async (id, tourData) => {
    return await api.put(`/api/tours/${id}`, tourData)
  },

  // Delete tour
  deleteTour: async (id) => {
    return await api.delete(`/api/tours/${id}`)
  },

  // Search tours
  searchTours: async (query, category) => {
    return await api.get('/api/tours/search', {
      params: { q: query, category }
    })
  },

  // Get featured tours
  getFeaturedTours: async () => {
    return await api.get('/api/tours/featured')
  },

  // Get tours by category
  getToursByCategory: async (categorySlug) => {
    return await api.get(`/api/tours/category/${categorySlug}`)
  },

  // Upload tour image
  uploadImage: async (file) => {
    const formData = new FormData();
    formData.append('file', file);
    return await api.post('/api/tours/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }
}