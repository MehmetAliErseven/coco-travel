import { Box, Container, Flex, Button, Stack, Link } from '@chakra-ui/react'
import { Link as RouterLink, useNavigate } from 'react-router-dom'
import { useTranslation } from 'react-i18next'
import LanguageSwitcher from './LanguageSwitcher'

const Header = () => {
  const navigate = useNavigate()
  const { t } = useTranslation()

  return (
    <Box as="header" bg="white" borderBottom="1px" borderColor="gray.200" py={4}>
      <Container maxW="container.xl">
        <Flex justify="space-between" align="center">
          {/* Logo */}
          <Link as={RouterLink} to="/" fontSize="2xl" fontWeight="bold">
            Travel Agency
          </Link>

          {/* Navigation */}
          <Stack direction="row" spacing={8}>
            <Link as={RouterLink} to="/">{t('common.home')}</Link>
            <Link as={RouterLink} to="/tours">{t('common.tours')}</Link>
            <Link as={RouterLink} to="/about">{t('common.about')}</Link>
            <Link as={RouterLink} to="/contact">{t('common.contact')}</Link>
          </Stack>

          <Flex gap={4} align="center">
            <LanguageSwitcher />
            <Button 
              colorScheme="blue"
              onClick={() => navigate('/contact')}
            >
              {t('common.contactUs')}
            </Button>
          </Flex>
        </Flex>
      </Container>
    </Box>
  )
}

export default Header