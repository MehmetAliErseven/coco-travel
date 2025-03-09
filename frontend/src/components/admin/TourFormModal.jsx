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
  Box,
  Image,
  IconButton,
  Wrap,
  WrapItem,
  useToast,
} from '@chakra-ui/react'
import { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { FaTrash, FaUpload } from 'react-icons/fa'
import { tourService } from '../../services/tourService'

const TourFormModal = ({ isOpen, onClose, tour = null, categories = [], onSave }) => {
  const { t } = useTranslation()
  const toast = useToast()
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [uploadingImage, setUploadingImage] = useState(false)
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

  const handleImageUpload = async (e) => {
    const file = e.target.files[0]
    if (!file) return

    try {
      setUploadingImage(true)
      const response = await tourService.uploadImage(file)
      setFormData(prev => ({
        ...prev,
        images: [...prev.images, response.url]
      }))
      toast({
        title: t('admin.form.imageUploadSuccess'),
        status: 'success',
        duration: 3000,
        isClosable: true,
      })
    } catch (error) {
      toast({
        title: t('admin.form.imageUploadError'),
        description: error.message,
        status: 'error',
        duration: 5000,
        isClosable: true,
      })
    } finally {
      setUploadingImage(false)
    }
  }

  const handleRemoveImage = (index) => {
    setFormData(prev => ({
      ...prev,
      images: prev.images.filter((_, i) => i !== index)
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
    <Modal
      isOpen={isOpen}
      onClose={onClose}
      size="xl"
      isCentered
    >
      <ModalOverlay />
      <ModalContent maxH="90vh">
        <ModalHeader>
          {t(tour ? 'admin.actions.edit' : 'admin.actions.add')}
        </ModalHeader>
        <ModalCloseButton />
        
        <form onSubmit={handleSubmit}>
          <ModalBody overflowY="auto" maxH="calc(90vh - 150px)">
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
                  type="file"
                  accept="image/*"
                  onChange={handleImageUpload}
                  display="none"
                  id="tour-image-upload"
                />
                <Button
                  as="label"
                  htmlFor="tour-image-upload"
                  leftIcon={<FaUpload />}
                  colorScheme="blue"
                  variant="outline"
                  cursor="pointer"
                  isLoading={uploadingImage}
                  mb={4}
                >
                  {t('admin.form.uploadImage')}
                </Button>
                
                <Wrap spacing={4}>
                  {formData.images.map((image, index) => (
                    <WrapItem key={index} position="relative">
                      <Box position="relative">
                        <Image
                          src={image}
                          alt={`Tour image ${index + 1}`}
                          boxSize="100px"
                          objectFit="cover"
                          borderRadius="md"
                        />
                        <IconButton
                          icon={<FaTrash />}
                          size="sm"
                          colorScheme="red"
                          position="absolute"
                          top={1}
                          right={1}
                          onClick={() => handleRemoveImage(index)}
                        />
                      </Box>
                    </WrapItem>
                  ))}
                </Wrap>
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