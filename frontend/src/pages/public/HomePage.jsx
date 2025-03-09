import { Box, Container, SimpleGrid, Heading, Text, Center, Flex } from '@chakra-ui/react'
import { useTranslation } from 'react-i18next'
import { useEffect, useState } from 'react'
import TourCard from '../../components/TourCard'
import SearchBar from '../../components/SearchBar'
import { tourService } from '../../services/tourService'
import { categoryService } from '../../services/categoryService'
import beachBg from '../../assets/images/cinematic-coconut-beach.jpg'

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
        
        const toursWithCategories = toursData.map(tour => {
          const tourWithCategory = {
            ...tour,
            category: categoriesData.find(cat => cat.id === tour.category_id)?.name || 'Uncategorized'
          }
          
          return tourWithCategory
        })
        
        setTours(toursWithCategories)
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
    <Box position="relative" overflow="hidden" margin={0} padding={0}>
      {/* Hero Section */}
      <Box
        as="section"
        position="relative"
        height="calc(100vh - 73px)"
        width="100vw"
        left="50%"
        right="50%"
        marginLeft="-50vw"
        marginRight="-50vw"
        padding={0}
        sx={{
          '&::before': {
            content: '""',
            position: 'absolute',
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            backgroundImage: `url(${beachBg})`,
            backgroundPosition: 'center',
            backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
            filter: 'brightness(0.85)',
            zIndex: -1
          }
        }}
      >
        <Container 
          maxW="container.xl" 
          height="100%" 
          position="relative"
          centerContent
          p={0}
        >
          <Flex
            direction="column"
            justify="center"
            align="center"
            height="100%"
            textAlign="center"
            width="100%"
            px={{ base: 4, md: 6, lg: 8 }}
          >
            <Heading
              size={{ base: "xl", md: "2xl" }}
              mb={4}
              textShadow="2px 2px 4px rgba(0,0,0,0.4)"
              color="white"
            >
              {t('home.hero.title')}
            </Heading>
            <Text
              fontSize={{ base: "lg", md: "xl" }}
              mb={8}
              textShadow="1px 1px 2px rgba(0,0,0,0.4)"
              maxW="800px"
              color="white"
            >
              {t('home.hero.subtitle')}
            </Text>
            <Box width={{ base: "95%", md: "auto" }}>
              <SearchBar categories={categories} variant="hero" />
            </Box>
          </Flex>
        </Container>
      </Box>

      {/* Tours Section */}
      <Container maxW="container.xl" py={{ base: 6, md: 10 }}>
        <Heading 
          size={{ base: "lg", md: "xl" }} 
          mb={{ base: 6, md: 8 }}
          px={{ base: 2, md: 0 }}
        >
          {t('home.featuredTours')}
        </Heading>
        <SimpleGrid 
          columns={{ base: 1, md: 2, lg: 3 }} 
          spacing={{ base: 4, md: 6, lg: 8 }}
          px={{ base: 2, md: 0 }}
        >
          {tours.map(tour => (
            <TourCard key={tour.id} {...tour} />
          ))}
        </SimpleGrid>
      </Container>
    </Box>
  )
}

export default HomePage