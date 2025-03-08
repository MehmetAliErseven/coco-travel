import {
  Box,
  Container,
  Flex,
  Heading,
  Stack,
  Button,
  Text,
  Menu,
  MenuButton,
  MenuList,
  MenuItem,
  IconButton,
  Divider,
  useColorModeValue,
} from '@chakra-ui/react'
import { Outlet, NavLink } from 'react-router-dom'
import { useTranslation } from 'react-i18next'
import { FaUserCircle, FaSignOutAlt } from 'react-icons/fa'
import { authService } from '../services/authService'
import { useLogoutConfirm } from '../hooks/useLogoutConfirm.jsx'

const AdminLayout = () => {
  const { t } = useTranslation()
  const user = authService.getCurrentUser()
  const bgColor = useColorModeValue('gray.50', 'gray.900')
  const borderColor = useColorModeValue('gray.200', 'gray.700')

  const { LogoutConfirmDialog, onOpen: onLogoutConfirm } = useLogoutConfirm(() => {
    authService.logout()
  })

  return (
    <Box minH="100vh" bg={bgColor}>
      {/* Header */}
      <Box bg="white" borderBottom="1px" borderColor={borderColor} py={4}>
        <Container maxW="container.xl">
          <Flex justify="space-between" align="center">
            <Stack direction="row" spacing={8} align="center">
              <Heading size="lg" color="blue.600">
                Admin Panel
              </Heading>
              <Stack direction="row" spacing={4}>
                <Button
                  as={NavLink}
                  to="/admin"
                  end
                  variant="ghost"
                  _activeLink={{ color: 'blue.500', bg: 'blue.50' }}
                >
                  {t('admin.dashboard')}
                </Button>
                <Button
                  as={NavLink}
                  to="/admin/tours"
                  variant="ghost"
                  _activeLink={{ color: 'blue.500', bg: 'blue.50' }}
                >
                  {t('admin.tours')}
                </Button>
                <Button
                  as={NavLink}
                  to="/admin/categories"
                  variant="ghost"
                  _activeLink={{ color: 'blue.500', bg: 'blue.50' }}
                >
                  {t('admin.categories')}
                </Button>
              </Stack>
            </Stack>

            <Menu>
              <MenuButton
                as={IconButton}
                icon={<FaUserCircle size="1.5em" />}
                variant="ghost"
                aria-label="User menu"
              />
              <MenuList>
                <Box px={4} py={2}>
                  <Text fontWeight="medium">{user?.email}</Text>
                  <Text fontSize="sm" color="gray.600">
                    {t('admin.role.administrator')}
                  </Text>
                </Box>
                <Divider />
                <MenuItem 
                  icon={<FaSignOutAlt />} 
                  onClick={onLogoutConfirm}
                  color="red.500"
                >
                  {t('admin.actions.logout')}
                </MenuItem>
              </MenuList>
            </Menu>
          </Flex>
        </Container>
      </Box>

      {/* Main Content */}
      <Container maxW="container.xl" py={8}>
        <Outlet />
      </Container>

      {/* Logout Confirmation Dialog */}
      <LogoutConfirmDialog />
    </Box>
  )
}

export default AdminLayout