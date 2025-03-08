import api from './api'

export const adminService = {
  // Get dashboard statistics
  getDashboardStats: async () => {
    return await api.get('/admin/dashboard/stats')
  },

  // Get recent activities
  getRecentActivities: async () => {
    return await api.get('/admin/dashboard/activities')
  },
  
  // Get unread notifications count
  getUnreadNotificationsCount: async () => {
    return await api.get('/admin/notifications/unread/count')
  },

  // Mark notification as read
  markNotificationAsRead: async (id) => {
    return await api.put(`/admin/notifications/${id}/read`)
  }
}