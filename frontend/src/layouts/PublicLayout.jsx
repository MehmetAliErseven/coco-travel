import { Box } from '@chakra-ui/react'
import { Outlet } from 'react-router-dom'
import Header from '../components/Header'
import Footer from '../components/Footer'

const PublicLayout = () => {
  return (
    <Box minH="100vh" display="flex" flexDirection="column">
      <Header />
      
      {/* Main Content */}
      <Box flex="1" as="main" p={4}>
        <Outlet />
      </Box>

      <Footer />
    </Box>
  )
}

export default PublicLayout