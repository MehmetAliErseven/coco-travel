import {
  Box,
  SimpleGrid,
  Stat,
  StatLabel,
  StatNumber,
  StatHelpText,
  Card,
  CardHeader,
  CardBody,
  Heading,
  List,
  ListItem,
  Text,
  Icon,
  Flex,
  useColorModeValue,
} from '@chakra-ui/react'
import { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { FaTachometerAlt, FaCalendarCheck, FaEnvelope, FaUsers } from 'react-icons/fa'
import { adminService } from '../../services/adminService'
import LoadingSpinner from '../../components/LoadingSpinner'

const StatCard = ({ title, value, icon, helpText }) => {
  const bgColor = useColorModeValue('white', 'gray.700')
  const iconColor = useColorModeValue('blue.500', 'blue.300')

  return (
    <Card bg={bgColor}>
      <CardBody>
        <Flex align="center" mb={2}>
          <Box mr={4}>
            <Icon as={icon} boxSize={8} color={iconColor} />
          </Box>
          <Stat>
            <StatLabel fontSize="lg">{title}</StatLabel>
            <StatNumber fontSize="3xl">{value}</StatNumber>
            {helpText && <StatHelpText>{helpText}</StatHelpText>}
          </Stat>
        </Flex>
      </CardBody>
    </Card>
  )
}

const AdminPanel = () => {
  const { t } = useTranslation()
  const [stats, setStats] = useState(null)
  const [activities, setActivities] = useState([])
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const fetchDashboardData = async () => {
      try {
        const [statsData, activitiesData] = await Promise.all([
          adminService.getDashboardStats(),
          adminService.getRecentActivities()
        ])
        setStats(statsData)
        setActivities(activitiesData)
      } catch (error) {
        console.error('Error fetching dashboard data:', error)
      } finally {
        setIsLoading(false)
      }
    }

    fetchDashboardData()
  }, [])

  if (isLoading) {
    return <LoadingSpinner message={t('admin.loading')} />
  }

  return (
    <Box>
      <SimpleGrid columns={{ base: 1, md: 2, lg: 4 }} spacing={6} mb={8}>
        <StatCard
          title={t('admin.stats.totalTours')}
          value={stats?.totalTours}
          icon={FaTachometerAlt}
        />
        <StatCard
          title={t('admin.stats.activeBookings')}
          value={stats?.activeBookings}
          icon={FaCalendarCheck}
        />
        <StatCard
          title={t('admin.stats.messages')}
          value={stats?.unreadMessages}
          helpText={t('admin.stats.unread')}
          icon={FaEnvelope}
        />
        <StatCard
          title={t('admin.stats.customers')}
          value={stats?.totalCustomers}
          icon={FaUsers}
        />
      </SimpleGrid>

      <Card>
        <CardHeader>
          <Heading size="md">{t('admin.recentActivities')}</Heading>
        </CardHeader>
        <CardBody>
          <List spacing={3}>
            {activities.map((activity) => (
              <ListItem key={activity.id}>
                <Text fontSize="sm" color="gray.500">
                  {new Date(activity.timestamp).toLocaleString()}
                </Text>
                <Text>{activity.description}</Text>
              </ListItem>
            ))}
          </List>
        </CardBody>
      </Card>
    </Box>
  )
}

export default AdminPanel