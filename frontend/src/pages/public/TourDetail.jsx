import { useParams } from 'react-router-dom'
import { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { 
  Box, 
  Container,
  Grid,
  GridItem,
  Image,
  Heading,
  Text,
  Stack,
  Badge,
  Button,
  List,
  ListItem,
  ListIcon,
  Divider,
  useToast,
  Skeleton,
} from '@chakra-ui/react'
import { CheckCircleIcon } from '@chakra-ui/icons'
import { FaWhatsapp } from 'react-icons/fa'
import { tourService } from '../../services/tourService'

const TourDetail = () => {
  const { id } = useParams()
  const { t } = useTranslation()
  const toast = useToast()
  const [tour, setTour] = useState(null)
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const fetchTourDetail = async () => {
      try {
        const data = await tourService.getTourById(id)
        setTour(data)
      } catch (error) {
        toast({
          title: 'Error',
          description: error.message,
          status: 'error',
          duration: 5000,
          isClosable: true,
        })
      } finally {
        setIsLoading(false)
      }
    }

    fetchTourDetail()
  }, [id, toast])

  const handleContactClick = () => {
    toast({
      title: t('contact.success'),
      description: t('contact.willContactSoon'),
      status: 'success',
      duration: 5000,
      isClosable: true,
    })
  }

  const getImageUrl = (images) => {
    if (!images || (Array.isArray(images) && images.length === 0)) {
      return null;
    }
    const imageUrl = Array.isArray(images) ? images[0] : images;
    return imageUrl.startsWith('http') ? imageUrl : `${import.meta.env.VITE_API_URL}${imageUrl}`;
  };

  if (isLoading) {
    return (
      <Container maxW="container.xl" py={8}>
        <Grid templateColumns={{ base: "1fr", lg: "2fr 1fr" }} gap={8}>
          <GridItem>
            <Skeleton height="400px" mb={6} />
            <Stack spacing={4}>
              <Skeleton height="40px" />
              <Skeleton height="20px" />
              <Skeleton height="100px" />
            </Stack>
          </GridItem>
          <GridItem>
            <Skeleton height="300px" />
          </GridItem>
        </Grid>
      </Container>
    )
  }

  if (!tour) {
    return (
      <Container maxW="container.xl" py={8}>
        <Box textAlign="center">
          <Heading>Tour not found</Heading>
        </Box>
      </Container>
    )
  }

  return (
    <Container maxW="container.xl" py={8}>
      <Grid templateColumns={{ base: "1fr", lg: "2fr 1fr" }} gap={8}>
        {/* Sol Kolon - Tour Detayları */}
        <GridItem>
          <Image
            src={getImageUrl(tour.images)}
            alt={tour.title}
            borderRadius="lg"
            w="100%"
            h="400px"
            objectFit="cover"
            mb={6}
            fallback={<Box height="400px" width="100%" bg="gray.100" />}
          />
          
          <Stack spacing={4}>
            <Box>
              <Badge colorScheme="blue" mb={2}>
                {tour.category?.name || "Uncategorized"}
              </Badge>
              <Heading size="xl">{tour.title}</Heading>
              <Text color="blue.600" fontSize="2xl" fontWeight="bold" mt={2}>
                {t('tour.pricePerPerson', { price: `$${tour.price}` })}
              </Text>
            </Box>

            <Text fontSize="lg">{tour.description}</Text>

            {Array.isArray(tour.includes) && tour.includes.length > 0 && (
              <Box>
                <Heading size="md" mb={4}>{t('tour.whatsIncluded')}:</Heading>
                <List spacing={3}>
                  {tour.includes.map((item, index) => (
                    <ListItem key={index}>
                      <ListIcon as={CheckCircleIcon} color="green.500" />
                      {item}
                    </ListItem>
                  ))}
                </List>
              </Box>
            )}

            {Array.isArray(tour.itinerary) && tour.itinerary.length > 0 && (
              <>
                <Divider />
                <Box>
                  <Heading size="md" mb={4}>{t('tour.itinerary')}:</Heading>
                  <List spacing={3}>
                    {tour.itinerary.map((item, index) => (
                      <ListItem key={index}>
                        {item}
                      </ListItem>
                    ))}
                  </List>
                </Box>
              </>
            )}
          </Stack>
        </GridItem>

        {/* Sağ Kolon - İletişim ve Rezervasyon */}
        <GridItem>
          <Box 
            position="sticky" 
            top={4} 
            p={6} 
            borderWidth="1px" 
            borderRadius="lg"
            shadow="md"
          >
            <Stack spacing={4}>
              <Heading size="md">{t('tour.bookTour')}</Heading>
              <Text>{t('common.duration')}: {tour.duration}</Text>
              <Text fontSize="2xl" fontWeight="bold" color="blue.600">
                {t('tour.pricePerPerson', { price: `$${tour.price}` })}
              </Text>
              
              <Button 
                colorScheme="green" 
                size="lg"
                leftIcon={<FaWhatsapp />}
                onClick={handleContactClick}
              >
                {t('tour.contactVia', { platform: 'WhatsApp' })}
              </Button>
              
              <Button 
                colorScheme="blue" 
                size="lg" 
                variant="outline"
                onClick={handleContactClick}
              >
                {t('tour.contactVia', { platform: 'Line' })}
              </Button>

              <Text fontSize="sm" color="gray.500" textAlign="center">
                {t('tour.orEmailUs', { email: 'cocotravel.agc@gmail.com' })}
              </Text>
            </Stack>
          </Box>
        </GridItem>
      </Grid>
    </Container>
  )
}

export default TourDetail