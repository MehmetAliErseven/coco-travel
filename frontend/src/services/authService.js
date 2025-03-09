import axios from 'axios'

const BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000'
const TOKEN_KEY = 'access_token'
const USER_KEY = 'user'

// Create a separate axios instance for auth to avoid circular dependency
const authApi = axios.create({
  baseURL: BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

// Add auth token to requests if it exists
authApi.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem(TOKEN_KEY)
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

export const authService = {
  login: async (credentials) => {
    try {
      // First get the token
      const formData = new URLSearchParams()
      formData.append('username', credentials.username)
      formData.append('password', credentials.password)

      const tokenResponse = await authApi.post('/api/auth/token', formData, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      })

      const { access_token } = tokenResponse.data
      localStorage.setItem(TOKEN_KEY, access_token)

      // Then get user info
      const userResponse = await authApi.get('/api/auth/me')
      const user = userResponse.data
      localStorage.setItem(USER_KEY, JSON.stringify(user))

      return { user, token: access_token }
    } catch (error) {
      localStorage.removeItem(TOKEN_KEY)
      localStorage.removeItem(USER_KEY)
      throw error
    }
  },

  logout: () => {
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
  },

  getToken: () => localStorage.getItem(TOKEN_KEY),

  getCurrentUser: () => {
    try {
      const userStr = localStorage.getItem(USER_KEY)
      return userStr ? JSON.parse(userStr) : null
    } catch {
      return null
    }
  },

  isAuthenticated: () => {
    const token = localStorage.getItem(TOKEN_KEY)
    const user = localStorage.getItem(USER_KEY)
    return !!(token && user)
  },

  updateCurrentUser: (user) => {
    localStorage.setItem(USER_KEY, JSON.stringify(user))
  }
}