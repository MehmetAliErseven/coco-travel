import { ChakraProvider } from '@chakra-ui/react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import theme from './theme'
import PublicLayout from './layouts/PublicLayout'
import AdminLayout from './layouts/AdminLayout'
import HomePage from './pages/public/HomePage'
import TourDetail from './pages/public/TourDetail'
import Contact from './pages/public/Contact'
import SearchResults from './pages/public/SearchResults'
import Login from './pages/public/Login'
import AdminPanel from './pages/admin/AdminPanel'
import ToursManagement from './pages/admin/ToursManagement'
import CategoriesManagement from './pages/admin/CategoriesManagement'
import PrivateRoute from './components/PrivateRoute'
import ErrorBoundary from './components/ErrorBoundary'
import { AuthProvider } from './hooks/useAuth.jsx'
import LoadingSpinner from './components/LoadingSpinner'

// Set up API interceptors
import api from './services/api'
import { authService } from './services/authService'

// Add auth token to requests
api.interceptors.request.use(config => {
  const token = authService.getToken()
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Handle token refresh on 401 errors
api.interceptors.response.use(
  response => response,
  async error => {
    const originalRequest = error.config

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      try {
        await authService.refreshToken()
        const token = authService.getToken()
        api.defaults.headers.Authorization = `Bearer ${token}`
        return api(originalRequest)
      } catch (refreshError) {
        authService.logout()
        return Promise.reject(refreshError)
      }
    }

    return Promise.reject(error)
  }
)

function App() {
  return (
    <ChakraProvider theme={theme}>
      <ErrorBoundary>
        <Router>
          <AuthProvider fallback={<LoadingSpinner />}>
            <Routes>
              {/* Public Routes */}
              <Route element={<PublicLayout />}>
                <Route path="/" element={<HomePage />} />
                <Route path="/tour/:id" element={<TourDetail />} />
                <Route path="/contact" element={<Contact />} />
                <Route path="/tours/search" element={<SearchResults />} />
                <Route path="/login" element={<Login />} />
              </Route>
              
              {/* Protected Admin Routes */}
              <Route
                path="/admin"
                element={
                  <PrivateRoute>
                    <AdminLayout />
                  </PrivateRoute>
                }
              >
                <Route index element={<AdminPanel />} />
                <Route path="tours" element={<ToursManagement />} />
                <Route path="categories" element={<CategoriesManagement />} />
              </Route>
            </Routes>
          </AuthProvider>
        </Router>
      </ErrorBoundary>
    </ChakraProvider>
  )
}

export default App
