import { Navigate, useLocation } from 'react-router-dom'
import { useState, useEffect } from 'react'
import { authService } from '../services/authService'
import LoadingSpinner from './LoadingSpinner'

const PrivateRoute = ({ children }) => {
  const location = useLocation()
  const [isAuthenticated, setIsAuthenticated] = useState(null)

  useEffect(() => {
    const checkAuth = async () => {
      try {
        // Check if we have token and user in localStorage
        const token = authService.getToken()
        const user = authService.getCurrentUser()

        if (!token || !user) {
          setIsAuthenticated(false)
          return
        }

        setIsAuthenticated(true)
      } catch (error) {
        console.error('Auth check failed:', error)
        setIsAuthenticated(false)
        authService.logout()
      }
    }

    checkAuth()
  }, [])

  if (isAuthenticated === null) {
    return <LoadingSpinner />
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" state={{ from: location }} replace />
  }

  return children
}

export default PrivateRoute