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
  useToast,
  Skeleton,
  Badge,
} from '@chakra-ui/react'
import { FaEllipsisV, FaPlus } from 'react-icons/fa'
import { useTranslation } from 'react-i18next'
import { useState, useEffect } from 'react'
import TourFormModal from '../../components/admin/TourFormModal'
import { tourService } from '../../services/tourService'
import { categoryService } from '../../services/categoryService'

const ToursManagement = () => {
  const { t } = useTranslation()
  const toast = useToast()
  const { isOpen, onOpen, onClose } = useDisclosure()
  const [selectedTour, setSelectedTour] = useState(null)
  const [tours, setTours] = useState([])
  const [categories, setCategories] = useState([])
  const [isLoading, setIsLoading] = useState(true)

  const fetchData = async () => {
    try {
      const [toursData, categoriesData] = await Promise.all([
        tourService.getTours({ active_only: false }),
        categoryService.getCategories({ active_only: false })
      ])
      setTours(toursData)
      setCategories(categoriesData)
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
    fetchData()
  }, [])

  const handleEdit = (tour) => {
    // Find the full category object for the tour
    const category = categories.find(c => c.id === tour.category_id)
    setSelectedTour({
      ...tour,
      category: category?.name // Add category name for display purposes
    })
    onOpen()
  }

  const handleAddNew = () => {
    setSelectedTour(null)
    onOpen()
  }

  const handleCloseModal = () => {
    setSelectedTour(null)
    onClose()
  }

  const handleDelete = async (id) => {
    if (window.confirm(t('admin.confirmDelete'))) {
      try {
        await tourService.deleteTour(id)
        toast({
          title: t('admin.deleteSuccess'),
          status: 'success',
          duration: 3000,
          isClosable: true,
        })
        fetchData()
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

  const handleDuplicate = async (tour) => {
    try {
      const { id, created_at, updated_at, slug, ...tourData } = tour
      tourData.title = `${tourData.title} (Copy)`
      await tourService.createTour(tourData)
      toast({
        title: t('admin.duplicateSuccess'),
        status: 'success',
        duration: 3000,
        isClosable: true,
      })
      fetchData()
    } catch (error) {
      toast({
        title: t('admin.duplicateError'),
        description: error.message,
        status: 'error',
        duration: 5000,
        isClosable: true,
      })
    }
  }

  const handleSave = async (formData) => {
    try {
      if (selectedTour) {
        await tourService.updateTour(selectedTour.id, formData)
      } else {
        await tourService.createTour(formData)
      }
      
      toast({
        title: selectedTour 
          ? t('admin.updateSuccess')
          : t('admin.createSuccess'),
        status: 'success',
        duration: 3000,
        isClosable: true,
      })
      
      handleCloseModal()
      fetchData()
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

  const getCategoryName = (categoryId) => {
    const category = categories.find(c => c.id === categoryId)
    return category ? category.name : 'Unknown Category'
  }

  return (
    <Box>
      <HStack justify="space-between" mb={6}>
        <Heading size="md">{t('admin.tours')}</Heading>
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
              <Th>Title</Th>
              <Th>Category</Th>
              <Th isNumeric>Price</Th>
              <Th>Duration</Th>
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
                  <Td><Skeleton height="20px" /></Td>
                </Tr>
              ))
            ) : (
              tours.map((tour) => (
                <Tr key={tour.id}>
                  <Td>{tour.title}</Td>
                  <Td>{getCategoryName(tour.category_id)}</Td>
                  <Td isNumeric>${tour.price}</Td>
                  <Td>{tour.duration}</Td>
                  <Td>
                    <Badge 
                      colorScheme={tour.is_active ? "green" : "red"}
                    >
                      {tour.is_active ? "Active" : "Inactive"}
                    </Badge>
                    {tour.featured && (
                      <Badge ml={2} colorScheme="purple">
                        Featured
                      </Badge>
                    )}
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
                        <MenuItem onClick={() => handleEdit(tour)}>
                          {t('admin.actions.edit')}
                        </MenuItem>
                        <MenuItem onClick={() => handleDuplicate(tour)}>
                          {t('admin.actions.duplicate')}
                        </MenuItem>
                        <MenuItem 
                          color="red.500"
                          onClick={() => handleDelete(tour.id)}
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

      <TourFormModal 
        isOpen={isOpen} 
        onClose={handleCloseModal}
        tour={selectedTour}
        categories={categories}
        onSave={handleSave}
      />
    </Box>
  )
}

export default ToursManagement