import { Box, Container, Flex, Button, Stack, Link, Image, IconButton, useDisclosure, Drawer, DrawerOverlay, DrawerContent, DrawerCloseButton, DrawerBody } from '@chakra-ui/react'
import { Link as RouterLink, useNavigate } from 'react-router-dom'
import { useTranslation } from 'react-i18next'
import { HamburgerIcon } from '@chakra-ui/icons'
import LanguageSwitcher from './LanguageSwitcher'
import logoImg from '../assets/images/coco-travel-logo.jpg'

const Header = () => {
  const navigate = useNavigate()
  const { t } = useTranslation()
  const { isOpen, onOpen, onClose } = useDisclosure()

  const NavigationLinks = () => (
    <>
      <Link as={RouterLink} to="/">{t('common.home')}</Link>
      <Link as={RouterLink} to="/tours">{t('common.tours')}</Link>
      <Link as={RouterLink} to="/about">{t('common.about')}</Link>
      <Link as={RouterLink} to="/contact">{t('common.contact')}</Link>
    </>
  )

  return (
    <Box as="header" bg="white" borderBottom="1px" borderColor="gray.200" py={4}>
      <Container maxW="container.xl">
        <Flex justify="space-between" align="center">
          {/* Logo */}
          <Link as={RouterLink} to="/">
            <Image src={logoImg} alt="Coco Travel" height={{ base: "30px", md: "40px" }} objectFit="contain" />
          </Link>

          {/* Desktop Navigation */}
          <Stack 
            direction="row" 
            spacing={8} 
            display={{ base: "none", md: "flex" }}
          >
            <NavigationLinks />
          </Stack>

          {/* Desktop Actions */}
          <Flex 
            gap={4} 
            align="center"
            display={{ base: "none", md: "flex" }}
          >
            <LanguageSwitcher />
            <Button 
              colorScheme="blue"
              onClick={() => navigate('/contact')}
              size={{ base: "sm", md: "md" }}
            >
              {t('common.contactUs')}
            </Button>
          </Flex>

          {/* Mobile Menu Button */}
          <IconButton
            display={{ base: "flex", md: "none" }}
            icon={<HamburgerIcon />}
            onClick={onOpen}
            variant="ghost"
            aria-label="Open menu"
          />

          {/* Mobile Menu Drawer */}
          <Drawer isOpen={isOpen} onClose={onClose} placement="right">
            <DrawerOverlay />
            <DrawerContent>
              <DrawerCloseButton />
              <DrawerBody pt={12}>
                <Stack spacing={4}>
                  <NavigationLinks />
                  <LanguageSwitcher />
                  <Button 
                    colorScheme="blue"
                    onClick={() => {
                      navigate('/contact')
                      onClose()
                    }}
                    size="md"
                    width="full"
                  >
                    {t('common.contactUs')}
                  </Button>
                </Stack>
              </DrawerBody>
            </DrawerContent>
          </Drawer>
        </Flex>
      </Container>
    </Box>
  )
}

export default Header