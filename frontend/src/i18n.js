import i18n from 'i18next'
import { initReactI18next } from 'react-i18next'

import enTranslations from './locales/en.json'
import thTranslations from './locales/th.json'

i18n
  .use(initReactI18next)
  .init({
    resources: {
      en: {
        translation: enTranslations
      },
      th: {
        translation: thTranslations
      }
    },
    lng: 'en', // varsayılan dil
    fallbackLng: 'en',
    interpolation: {
      escapeValue: false // React zaten XSS'e karşı koruma sağlıyor
    }
  })

export default i18n