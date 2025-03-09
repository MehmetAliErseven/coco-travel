import { useState, createContext, useContext, useCallback } from 'react'
import { useNavigate } from 'react-router-dom'
import { authService } from '../services/authService'
import { useToast } from '@chakra-ui/react'

const AuthContext = createContext(null)

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(authService.getCurrentUser())
  const navigate = useNavigate()
  const toast = useToast()

  const login = useCallback(async (credentials) => {
    try {
      const response = await authService.login(credentials)
      setUser(response.user)
      return response
    } catch (error) {
      toast({
        title: 'Login Failed',
        description: error.message,
        status: 'error',
        duration: 5000,
        isClosable: true,
      })
      throw error
    }
  }, [toast])

  const logout = useCallback(() => {
    authService.logout()
    setUser(null)
    navigate('/login')
  }, [navigate])

  const value = {
    user,
    isAuthenticated: !!user,
    login,
    logout
  }

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  )
}

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  return context
}

export default useAuth