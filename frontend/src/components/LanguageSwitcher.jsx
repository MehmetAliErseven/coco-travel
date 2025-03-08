import { Select } from '@chakra-ui/react'
import { useTranslation } from 'react-i18next'

const LanguageSwitcher = () => {
  const { i18n } = useTranslation()

  const changeLanguage = (event) => {
    const language = event.target.value
    i18n.changeLanguage(language)
  }

  return (
    <Select 
      value={i18n.language} 
      onChange={changeLanguage}
      width="auto"
      size="sm"
    >
      <option value="en">English</option>
      <option value="th">ไทย</option>
    </Select>
  )
}

export default LanguageSwitcher