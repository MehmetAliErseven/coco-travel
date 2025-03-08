import api from './api'

const TOKEN_KEY = 'auth_token'
const REFRESH_TOKEN_KEY = 'refresh_token'
const USER_KEY = 'user'

export const authService = {
  login: async (credentials) => {
    const response = await api.post('/auth/login', credentials)
    localStorage.setItem(TOKEN_KEY, response.token)
    localStorage.setItem(REFRESH_TOKEN_KEY, response.refreshToken)
    localStorage.setItem(USER_KEY, JSON.stringify(response.user))
    return response
  },

  logout: () => {
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(REFRESH_TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
    // Clear auth headers
    delete api.defaults.headers.Authorization
  },

  refreshToken: async () => {
    const refreshToken = localStorage.getItem(REFRESH_TOKEN_KEY)
    if (!refreshToken) {
      throw new Error('No refresh token available')
    }

    try {
      const response = await api.post('/auth/refresh', { refreshToken })
      localStorage.setItem(TOKEN_KEY, response.token)
      return response.token
    } catch (error) {
      // If refresh fails, clear auth state
      authService.logout()
      throw error
    }
  },

  getToken: () => localStorage.getItem(TOKEN_KEY),

  getCurrentUser: () => {
    const userStr = localStorage.getItem(USER_KEY)
    return userStr ? JSON.parse(userStr) : null
  },

  isAuthenticated: () => {
    return !!localStorage.getItem(TOKEN_KEY)
  },

  updateCurrentUser: (user) => {
    localStorage.setItem(USER_KEY, JSON.stringify(user))
  }
}