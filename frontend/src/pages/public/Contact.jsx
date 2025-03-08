import {
  Box,
  Container,
  Heading,
  FormControl,
  FormLabel,
  Input,
  Textarea,
  Button,
  VStack,
  useToast,
  Text,
  SimpleGrid,
  Icon,
} from '@chakra-ui/react'
import { useState } from 'react'
import { useTranslation } from 'react-i18next'
import { FaWhatsapp, FaLine, FaEnvelope, FaMapMarker } from 'react-icons/fa'
import { contactService } from '../../services/contactService'

const Contact = () => {
  const { t } = useTranslation()
  const toast = useToast()
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  })

  const handleSubmit = async (e) => {
    e.preventDefault()
    setIsSubmitting(true)

    try {
      await contactService.sendMessage(formData)
      
      toast({
        title: t('contact.success'),
        status: 'success',
        duration: 5000,
        isClosable: true,
      })
      
      setFormData({
        name: '',
        email: '',
        subject: '',
        message: ''
      })
    } catch (error) {
      toast({
        title: t('contact.error'),
        description: error.message,
        status: 'error',
        duration: 5000,
        isClosable: true,
      })
    } finally {
      setIsSubmitting(false)
    }
  }

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: value
    }))
  }

  return (
    <Container maxW="container.xl" py={10}>
      <SimpleGrid columns={{ base: 1, md: 2 }} spacing={10}>
        {/* Contact Form */}
        <VStack as="form" spacing={6} onSubmit={handleSubmit}>
          <Heading w="full">{t('contact.title')}</Heading>
          
          <FormControl isRequired>
            <FormLabel>{t('contact.form.name')}</FormLabel>
            <Input
              name="name"
              value={formData.name}
              onChange={handleChange}
              placeholder={t('contact.form.placeholders.name')}
            />
          </FormControl>

          <FormControl isRequired>
            <FormLabel>{t('contact.form.email')}</FormLabel>
            <Input
              name="email"
              type="email"
              value={formData.email}
              onChange={handleChange}
              placeholder={t('contact.form.placeholders.email')}
            />
          </FormControl>

          <FormControl isRequired>
            <FormLabel>{t('contact.form.subject')}</FormLabel>
            <Input
              name="subject"
              value={formData.subject}
              onChange={handleChange}
              placeholder={t('contact.form.placeholders.subject')}
            />
          </FormControl>

          <FormControl isRequired>
            <FormLabel>{t('contact.form.message')}</FormLabel>
            <Textarea
              name="message"
              value={formData.message}
              onChange={handleChange}
              placeholder={t('contact.form.placeholders.message')}
              rows={6}
            />
          </FormControl>

          <Button
            type="submit"
            colorScheme="blue"
            size="lg"
            w="full"
            isLoading={isSubmitting}
          >
            {t('contact.form.send')}
          </Button>
        </VStack>

        {/* Contact Information */}
        <VStack spacing={8} align="start">
          <Heading>{t('contact.getInTouch')}</Heading>
          
          <VStack spacing={4} align="start">
            <Box>
              <Text fontSize="lg" fontWeight="bold" mb={2}>
                <Icon as={FaWhatsapp} mr={2} color="green.500" />
                WhatsApp
              </Text>
              <Text>+1 234 567 8900</Text>
            </Box>

            <Box>
              <Text fontSize="lg" fontWeight="bold" mb={2}>
                <Icon as={FaLine} mr={2} color="green.400" />
                Line
              </Text>
              <Text>@travelagency</Text>
            </Box>

            <Box>
              <Text fontSize="lg" fontWeight="bold" mb={2}>
                <Icon as={FaEnvelope} mr={2} color="blue.500" />
                Email
              </Text>
              <Text>info@travelagency.com</Text>
            </Box>

            <Box>
              <Text fontSize="lg" fontWeight="bold" mb={2}>
                <Icon as={FaMapMarker} mr={2} color="red.500" />
                {t('common.address')}
              </Text>
              <Text>123 Travel Street,<br />Tourism District,<br />12345</Text>
            </Box>
          </VStack>
        </VStack>
      </SimpleGrid>
    </Container>
  )
}

export default Contact