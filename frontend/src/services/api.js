import axios from 'axios'
import { authService } from './authService'

const api = axios.create({
  // In development, we use relative URLs which will be handled by Vite's proxy
  // In production, we use the full URL from environment variables
  baseURL: '/',
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: false
})

// Add auth token to requests
api.interceptors.request.use(config => {
  const token = authService.getToken()
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Handle responses
api.interceptors.response.use(
  response => response.data,
  error => {
    if (error.response?.status === 401) {
      authService.logout()
      window.location.href = '/login'
    }
    return Promise.reject(error.response?.data || error)
  }
)

export default api