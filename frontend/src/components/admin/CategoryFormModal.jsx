import {
  Modal,
  ModalOverlay,
  ModalContent,
  ModalHeader,
  ModalFooter,
  ModalBody,
  ModalCloseButton,
  Button,
  FormControl,
  FormLabel,
  Input,
  VStack,
  Switch,
  FormHelperText,
} from '@chakra-ui/react'
import { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'

const CategoryFormModal = ({ isOpen, onClose, category = null, onSave }) => {
  const { t } = useTranslation()
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [formData, setFormData] = useState({
    name: '',
    slug: '',
    is_active: true
  })

  useEffect(() => {
    if (category) {
      setFormData(category)
    } else {
      setFormData({
        name: '',
        slug: '',
        is_active: true
      })
    }
  }, [category])

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }))
  }

  const generateSlug = (name) => {
    return name.toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)+/g, '')
  }

  const handleNameChange = (e) => {
    const { value } = e.target
    setFormData(prev => ({
      ...prev,
      name: value,
      slug: generateSlug(value)
    }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setIsSubmitting(true)
    try {
      await onSave(formData)
      onClose()
    } catch (error) {
      console.error('Error submitting form:', error)
    } finally {
      setIsSubmitting(false)
    }
  }

  return (
    <Modal isOpen={isOpen} onClose={onClose}>
      <ModalOverlay />
      <ModalContent>
        <ModalHeader>
          {t(category ? 'admin.actions.edit' : 'admin.actions.add')}
        </ModalHeader>
        <ModalCloseButton />
        
        <form onSubmit={handleSubmit}>
          <ModalBody>
            <VStack spacing={4}>
              <FormControl isRequired>
                <FormLabel>{t('admin.form.name')}</FormLabel>
                <Input
                  name="name"
                  value={formData.name}
                  onChange={handleNameChange}
                />
              </FormControl>

              <FormControl>
                <FormLabel>{t('admin.form.slug')}</FormLabel>
                <Input
                  name="slug"
                  value={formData.slug}
                  onChange={handleChange}
                  placeholder="auto-generated-slug"
                />
                <FormHelperText>
                  {t('admin.form.slugHelper')}
                </FormHelperText>
              </FormControl>

              <FormControl display="flex" alignItems="center">
                <FormLabel htmlFor="is_active" mb="0">
                  {t('admin.form.active')}
                </FormLabel>
                <Switch
                  id="is_active"
                  name="is_active"
                  isChecked={formData.is_active}
                  onChange={handleChange}
                />
              </FormControl>
            </VStack>
          </ModalBody>

          <ModalFooter>
            <Button 
              variant="ghost" 
              mr={3} 
              onClick={onClose}
              isDisabled={isSubmitting}
            >
              {t('admin.actions.cancel')}
            </Button>
            <Button 
              colorScheme="blue" 
              type="submit"
              isLoading={isSubmitting}
            >
              {t(category ? 'admin.actions.save' : 'admin.actions.create')}
            </Button>
          </ModalFooter>
        </form>
      </ModalContent>
    </Modal>
  )
}

export default CategoryFormModal