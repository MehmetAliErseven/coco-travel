import api from './api'

export const contactService = {
  // İletişim formu mesajı gönder
  sendMessage: async (messageData) => {
    return await api.post('/contact', messageData)
  },

  // Admin: Tüm mesajları getir
  getMessages: async (params = {}) => {
    return await api.get('/admin/messages', { params })
  },

  // Admin: Mesaj detayını getir
  getMessageById: async (id) => {
    return await api.get(`/admin/messages/${id}`)
  },

  // Admin: Mesajı okundu olarak işaretle
  markAsRead: async (id) => {
    return await api.put(`/admin/messages/${id}/read`)
  },

  // Admin: Mesajı sil
  deleteMessage: async (id) => {
    return await api.delete(`/admin/messages/${id}`)
  },

  // Admin: Okunmamış mesaj sayısını getir
  getUnreadCount: async () => {
    return await api.get('/admin/messages/unread/count')
  }
}