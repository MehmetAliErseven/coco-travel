import axios from 'axios'
import { authService } from './authService'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json'
  }
})

// Request interceptor for adding auth token
api.interceptors.request.use(
  (config) => {
    const token = authService.getToken()
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor for handling errors
api.interceptors.response.use(
  (response) => {
    // If the response has a data property, return it, otherwise return the whole response
    return response.data || response
  },
  async (error) => {
    const originalRequest = error.config

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      // If unauthorized, logout the user
      authService.logout()
      window.location.href = '/login'
      return Promise.reject(error)
    }

    // Extract error message from response
    const errorMessage = error.response?.data?.detail || error.response?.data?.message || error.message
    return Promise.reject({
      ...error,
      message: errorMessage
    })
  }
)

export default api