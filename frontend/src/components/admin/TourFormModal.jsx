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
  Select,
  Textarea,
  VStack,
  NumberInput,
  NumberInputField,
  Switch,
  FormHelperText,
} from '@chakra-ui/react'
import { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'

const TourFormModal = ({ isOpen, onClose, tour = null, categories = [], onSave }) => {
  const { t } = useTranslation()
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [formData, setFormData] = useState({
    title: '',
    category_id: '',
    price: '',
    duration: '',
    location: '',
    description: '',
    includes: '',
    itinerary: '',
    images: [],
    featured: false,
    is_active: true
  })

  useEffect(() => {
    if (tour) {
      setFormData({
        ...tour,
        category_id: tour.category_id?.toString(),
        includes: Array.isArray(tour.includes) ? tour.includes.join('\n') : tour.includes || '',
        itinerary: Array.isArray(tour.itinerary) ? tour.itinerary.join('\n') : tour.itinerary || '',
        images: Array.isArray(tour.images) ? tour.images : tour.images ? [tour.images] : []
      })
    } else {
      setFormData({
        title: '',
        category_id: '',
        price: '',
        duration: '',
        location: '',
        description: '',
        includes: '',
        itinerary: '',
        images: [],
        featured: false,
        is_active: true
      })
    }
  }, [tour])

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }))
  }

  const handleNumberChange = (name, value) => {
    setFormData(prev => ({
      ...prev,
      [name]: value
    }))
  }

  const handleImagesChange = (e) => {
    const value = e.target.value
    // Split by commas and trim whitespace
    const imageArray = value.split(',').map(url => url.trim()).filter(url => url)
    setFormData(prev => ({
      ...prev,
      images: imageArray
    }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setIsSubmitting(true)

    try {
      // Process form data to match API requirements
      const processedData = {
        ...formData,
        includes: formData.includes.split('\n').filter(item => item.trim()),
        itinerary: formData.itinerary.split('\n').filter(item => item.trim()),
        price: Number(formData.price),
        category_id: parseInt(formData.category_id),
        is_active: formData.is_active
      }

      await onSave(processedData)
    } catch (error) {
      console.error('Error submitting form:', error)
    } finally {
      setIsSubmitting(false)
    }
  }

  return (
    <Modal isOpen={isOpen} onClose={onClose} size="xl">
      <ModalOverlay />
      <ModalContent>
        <ModalHeader>
          {t(tour ? 'admin.actions.edit' : 'admin.actions.add')}
        </ModalHeader>
        <ModalCloseButton />
        
        <form onSubmit={handleSubmit}>
          <ModalBody>
            <VStack spacing={4}>
              <FormControl isRequired>
                <FormLabel>{t('admin.form.title')}</FormLabel>
                <Input
                  name="title"
                  value={formData.title}
                  onChange={handleChange}
                />
              </FormControl>

              <FormControl isRequired>
                <FormLabel>{t('admin.form.category')}</FormLabel>
                <Select
                  name="category_id"
                  value={formData.category_id}
                  onChange={handleChange}
                  placeholder={t('common.allCategories')}
                >
                  {categories.map((category) => (
                    <option key={category.id} value={category.id}>
                      {category.name}
                    </option>
                  ))}
                </Select>
              </FormControl>

              <FormControl isRequired>
                <FormLabel>{t('admin.form.location')}</FormLabel>
                <Input
                  name="location"
                  value={formData.location}
                  onChange={handleChange}
                />
              </FormControl>

              <FormControl isRequired>
                <FormLabel>{t('admin.form.price')}</FormLabel>
                <NumberInput
                  min={0}
                  value={formData.price}
                  onChange={(value) => handleNumberChange('price', value)}
                >
                  <NumberInputField name="price" />
                </NumberInput>
              </FormControl>

              <FormControl isRequired>
                <FormLabel>{t('admin.form.duration')}</FormLabel>
                <Input
                  name="duration"
                  value={formData.duration}
                  onChange={handleChange}
                  placeholder="e.g., Full Day, 3 Days"
                />
              </FormControl>

              <FormControl isRequired>
                <FormLabel>{t('admin.form.description')}</FormLabel>
                <Textarea
                  name="description"
                  value={formData.description}
                  onChange={handleChange}
                  rows={4}
                />
              </FormControl>

              <FormControl>
                <FormLabel>{t('admin.form.includes')}</FormLabel>
                <Textarea
                  name="includes"
                  value={formData.includes}
                  onChange={handleChange}
                  placeholder={t('admin.form.includesPlaceholder')}
                  rows={4}
                />
                <FormHelperText>
                  {t('admin.form.includesHelper')}
                </FormHelperText>
              </FormControl>

              <FormControl>
                <FormLabel>{t('admin.form.itinerary')}</FormLabel>
                <Textarea
                  name="itinerary"
                  value={formData.itinerary}
                  onChange={handleChange}
                  placeholder={t('admin.form.itineraryPlaceholder')}
                  rows={4}
                />
                <FormHelperText>
                  {t('admin.form.itineraryHelper')}
                </FormHelperText>
              </FormControl>

              <FormControl>
                <FormLabel>{t('admin.form.images')}</FormLabel>
                <Input
                  name="images"
                  value={formData.images.join(', ')}
                  onChange={handleImagesChange}
                  placeholder="Enter image URLs separated by commas"
                />
                <FormHelperText>
                  Enter multiple image URLs separated by commas
                </FormHelperText>
              </FormControl>

              <FormControl display="flex" alignItems="center">
                <FormLabel htmlFor="featured" mb="0">
                  Featured Tour
                </FormLabel>
                <Switch
                  id="featured"
                  name="featured"
                  isChecked={formData.featured}
                  onChange={handleChange}
                />
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
              {t(tour ? 'admin.actions.save' : 'admin.actions.create')}
            </Button>
          </ModalFooter>
        </form>
      </ModalContent>
    </Modal>
  )
}

export default TourFormModal