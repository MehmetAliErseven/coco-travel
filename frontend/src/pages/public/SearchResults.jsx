import { useEffect, useState } from 'react'
import { useSearchParams } from 'react-router-dom'
import {
  Box,
  Container,
  SimpleGrid,
  Heading,
  Text,
  Stack,
  Skeleton,
} from '@chakra-ui/react'
import { useTranslation } from 'react-i18next'
import TourCard from '../../components/TourCard'
import SearchBar from '../../components/SearchBar'
import { tourService } from '../../services/tourService'
import { categoryService } from '../../services/categoryService'

const SearchResults = () => {
  const [searchParams] = useSearchParams()
  const { t } = useTranslation()
  const [isLoading, setIsLoading] = useState(true)
  const [results, setResults] = useState([])
  const [categories, setCategories] = useState([])

  const query = searchParams.get('q')
  const category = searchParams.get('category')

  useEffect(() => {
    const fetchData = async () => {
      setIsLoading(true)
      try {
        // Kategorileri yükle
        const categoriesData = await categoryService.getActiveCategories()
        setCategories(categoriesData)

        // Arama sonuçlarını yükle
        const searchResults = await tourService.searchTours(query, category)
        setResults(searchResults)
      } catch (error) {
        console.error('Error fetching results:', error)
      } finally {
        setIsLoading(false)
      }
    }

    fetchData()
  }, [query, category])

  return (
    <Container maxW="container.xl" py={8}>
      <Stack spacing={8}>
        <Box>
          <SearchBar categories={categories} />
        </Box>

        <Box>
          <Heading size="lg" mb={2}>{t('search.results')}</Heading>
          <Text color="gray.600" mb={6}>
            {query ? t('search.showing', { query }) : t('home.featuredTours')}
            {category && categories.find(c => c.slug === category)?.name && 
              t('search.inCategory', { 
                category: categories.find(c => c.slug === category)?.name 
              })
            }
          </Text>

          {isLoading ? (
            <SimpleGrid columns={{ base: 1, md: 2, lg: 3 }} spacing={8}>
              {[1, 2, 3].map((i) => (
                <Skeleton key={i} height="400px" borderRadius="lg" />
              ))}
            </SimpleGrid>
          ) : results.length > 0 ? (
            <SimpleGrid columns={{ base: 1, md: 2, lg: 3 }} spacing={8}>
              {results.map(tour => (
                <TourCard key={tour.id} {...tour} />
              ))}
            </SimpleGrid>
          ) : (
            <Box textAlign="center" py={10}>
              <Text fontSize="lg" color="gray.600">
                {t('search.noResults')}
              </Text>
            </Box>
          )}
        </Box>
      </Stack>
    </Container>
  )
}

export default SearchResults