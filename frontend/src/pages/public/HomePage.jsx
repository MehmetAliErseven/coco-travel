import { Box, Container, SimpleGrid, Heading, Text, Center } from '@chakra-ui/react'
import { useTranslation } from 'react-i18next'
import { useEffect, useState } from 'react'
import TourCard from '../../components/TourCard'
import SearchBar from '../../components/SearchBar'
import { tourService } from '../../services/tourService'
import { categoryService } from '../../services/categoryService'

const HomePage = () => {
  const { t } = useTranslation()
  const [tours, setTours] = useState([])
  const [categories, setCategories] = useState([])
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [toursData, categoriesData] = await Promise.all([
          tourService.getFeaturedTours(),
          categoryService.getActiveCategories()
        ])
        setTours(toursData)
        setCategories(categoriesData)
      } catch (error) {
        console.error('Error fetching data:', error)
      } finally {
        setIsLoading(false)
      }
    }

    fetchData()
  }, [])

  return (
    <Box>
      {/* Hero Section */}
      <Box bg="blue.500" color="white" py={20}>
        <Container maxW="container.xl">
          <Box textAlign="center" mb={10}>
            <Heading size="2xl" mb={4}>
              {t('home.hero.title')}
            </Heading>
            <Text fontSize="xl" mb={8}>
              {t('home.hero.subtitle')}
            </Text>
          </Box>
          
          <Center>
            <SearchBar categories={categories} variant="hero" />
          </Center>
        </Container>
      </Box>

      {/* Tours Section */}
      <Container maxW="container.xl" py={10}>
        <Heading size="xl" mb={8}>{t('home.featuredTours')}</Heading>
        <SimpleGrid columns={{ base: 1, md: 2, lg: 3 }} spacing={8}>
          {tours.map(tour => (
            <TourCard key={tour.id} {...tour} />
          ))}
        </SimpleGrid>
      </Container>
    </Box>
  )
}

export default HomePage