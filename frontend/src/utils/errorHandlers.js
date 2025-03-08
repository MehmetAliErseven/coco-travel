import { authService } from '../services/authService'

export const handleApiError = (error, toast) => {
  // Handle unauthorized errors
  if (error.response?.status === 401) {
    authService.logout()
    return
  }

  // Handle other API errors
  toast({
    title: error.response?.data?.message || 'Error',
    description: error.message,
    status: 'error',
    duration: 5000,
    isClosable: true,
  })
}

export const withErrorHandler = (fn, toast) => {
  return async (...args) => {
    try {
      return await fn(...args)
    } catch (error) {
      handleApiError(error, toast)
      throw error
    }
  }
}