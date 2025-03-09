import {
  Box,
  Button,
  Table,
  Thead,
  Tbody,
  Tr,
  Th,
  Td,
  IconButton,
  Menu,
  MenuButton,
  MenuList,
  MenuItem,
  useDisclosure,
  Heading,
  HStack,
  Badge,
  useToast,
  Skeleton,
} from '@chakra-ui/react'
import { FaEllipsisV, FaPlus } from 'react-icons/fa'
import { useTranslation } from 'react-i18next'
import { useState, useEffect } from 'react'
import CategoryFormModal from '../../components/admin/CategoryFormModal'
import { categoryService } from '../../services/categoryService'

const CategoriesManagement = () => {
  const { t } = useTranslation()
  const toast = useToast()
  const { isOpen, onOpen, onClose } = useDisclosure()
  const [selectedCategory, setSelectedCategory] = useState(null)
  const [categories, setCategories] = useState([])
  const [isLoading, setIsLoading] = useState(true)

  const fetchCategories = async () => {
    try {
      const data = await categoryService.getCategories()
      setCategories(data)
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

  useEffect(() => {
    fetchCategories()
  }, [])

  const handleEdit = (category) => {
    setSelectedCategory(category)
    onOpen()
  }

  const handleAddNew = () => {
    setSelectedCategory(null)
    onOpen()
  }

  const handleCloseModal = () => {
    setSelectedCategory(null)
    onClose()
  }

  const handleDelete = async (id) => {
    if (window.confirm(t('admin.confirmDelete'))) {
      try {
        await categoryService.deleteCategory(id)
        toast({
          title: t('admin.deleteSuccess'),
          status: 'success',
          duration: 3000,
          isClosable: true,
        })
        fetchCategories()
      } catch (error) {
        toast({
          title: t('admin.deleteError'),
          description: error.message,
          status: 'error',
          duration: 5000,
          isClosable: true,
        })
      }
    }
  }

  const handleSave = async (formData) => {
    try {
      if (selectedCategory) {
        await categoryService.updateCategory(selectedCategory.id, formData)
      } else {
        await categoryService.createCategory(formData)
      }
      
      toast({
        title: selectedCategory 
          ? t('admin.updateSuccess')
          : t('admin.createSuccess'),
        status: 'success',
        duration: 3000,
        isClosable: true,
      })
      
      handleCloseModal()
      fetchCategories()
    } catch (error) {
      toast({
        title: 'Error',
        description: error.message,
        status: 'error',
        duration: 5000,
        isClosable: true,
      })
    }
  }

  return (
    <Box>
      <HStack justify="space-between" mb={6}>
        <Heading size="md">{t('admin.categories')}</Heading>
        <Button 
          leftIcon={<FaPlus />} 
          colorScheme="blue" 
          onClick={handleAddNew}
        >
          {t('admin.actions.add')}
        </Button>
      </HStack>

      <Box bg="white" rounded="lg" shadow="sm" overflow="hidden">
        <Table variant="simple">
          <Thead>
            <Tr>
              <Th>Name</Th>
              <Th>Slug</Th>
              <Th isNumeric>Tour Count</Th>
              <Th>Status</Th>
              <Th></Th>
            </Tr>
          </Thead>
          <Tbody>
            {isLoading ? (
              [1, 2, 3].map((i) => (
                <Tr key={i}>
                  <Td><Skeleton height="20px" /></Td>
                  <Td><Skeleton height="20px" /></Td>
                  <Td><Skeleton height="20px" /></Td>
                  <Td><Skeleton height="20px" /></Td>
                  <Td><Skeleton height="20px" /></Td>
                </Tr>
              ))
            ) : (
              categories.map((category) => (
                <Tr key={category.id}>
                  <Td fontWeight="medium">{category.name}</Td>
                  <Td color="gray.600">{category.slug}</Td>
                  <Td isNumeric>{category.tourCount}</Td>
                  <Td>
                    <Badge
                      colorScheme={category.is_active ? 'green' : 'gray'}
                      borderRadius="full"
                      px={2}
                    >
                      {category.is_active ? 'Active' : 'Inactive'}
                    </Badge>
                  </Td>
                  <Td>
                    <Menu>
                      <MenuButton
                        as={IconButton}
                        icon={<FaEllipsisV />}
                        variant="ghost"
                        size="sm"
                      />
                      <MenuList>
                        <MenuItem onClick={() => handleEdit(category)}>
                          {t('admin.actions.edit')}
                        </MenuItem>
                        <MenuItem 
                          color="red.500"
                          onClick={() => handleDelete(category.id)}
                        >
                          {t('admin.actions.delete')}
                        </MenuItem>
                      </MenuList>
                    </Menu>
                  </Td>
                </Tr>
              ))
            )}
          </Tbody>
        </Table>
      </Box>

      <CategoryFormModal 
        isOpen={isOpen} 
        onClose={handleCloseModal}
        category={selectedCategory}
        onSave={handleSave}
      />
    </Box>
  )
}

export default CategoriesManagement