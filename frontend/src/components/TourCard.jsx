import { Box, Image, Badge, Text, Stack, Heading, Button } from '@chakra-ui/react'
import { useNavigate } from 'react-router-dom'
import { useTranslation } from 'react-i18next'

const TourCard = ({ id, title, description, price, duration, image, category }) => {
  const navigate = useNavigate()
  const { t } = useTranslation()

  return (
    <Box 
      maxW="sm" 
      borderWidth="1px" 
      borderRadius="lg" 
      overflow="hidden"
      _hover={{ 
        transform: 'translateY(-4px)', 
        transition: 'all 0.2s ease-in-out',
        shadow: 'lg'
      }}
    >
      <Image 
        src={image || 'https://via.placeholder.com/300x200'} 
        alt={title}
        height="200px"
        width="100%"
        objectFit="cover"
      />

      <Box p="6">
        <Box display="flex" alignItems="baseline">
          <Badge borderRadius="full" px="2" colorScheme="blue">
            {category}
          </Badge>
          <Text
            ml={2}
            textTransform="uppercase"
            fontSize="sm"
            fontWeight="bold"
            color="gray.500"
          >
            {duration}
          </Text>
        </Box>

        <Heading size="md" mt={2} mb={2}>
          {title}
        </Heading>

        <Text color="gray.500" noOfLines={2}>
          {description}
        </Text>

        <Stack direction="row" mt={4} justify="space-between" align="center">
          <Text fontSize="2xl" fontWeight="bold">
            ${price}
          </Text>
          <Button 
            colorScheme="blue" 
            onClick={() => navigate(`/tour/${id}`)}
          >
            {t('common.viewDetails')}
          </Button>
        </Stack>
      </Box>
    </Box>
  )
}

export default TourCard