import { Box, Container, Stack, Text, Link, SimpleGrid } from '@chakra-ui/react'
import { Link as RouterLink } from 'react-router-dom'

const Footer = () => {
  return (
    <Box as="footer" bg="gray.50" borderTop="1px" borderColor="gray.200" py={10}>
      <Container maxW="container.xl">
        <SimpleGrid columns={{ base: 1, md: 4 }} spacing={8}>
          {/* Company Info */}
          <Stack spacing={4}>
            <Text fontSize="lg" fontWeight="bold">Coco Travel</Text>
            <Text color="gray.600">
              Your trusted partner for unforgettable travel experiences
            </Text>
          </Stack>

          {/* Quick Links */}
          <Stack spacing={4}>
            <Text fontWeight="bold">Quick Links</Text>
            <Link as={RouterLink} to="/tours">Tours</Link>
            <Link as={RouterLink} to="/about">About Us</Link>
            <Link as={RouterLink} to="/contact">Contact</Link>
          </Stack>

          {/* Tour Categories */}
          <Stack spacing={4}>
            <Text fontWeight="bold">Categories</Text>
            <Link as={RouterLink} to="/tours/daily">Daily Tours</Link>
            <Link as={RouterLink} to="/tours/package">Package Tours</Link>
            <Link as={RouterLink} to="/tours/speedboat">Speedboat Tours</Link>
          </Stack>

          {/* Contact Info */}
          <Stack spacing={4}>
            <Text fontWeight="bold">Contact Us</Text>
            <Text>Email: cocotravel.agc@gmail.com</Text>
            <Text>Phone: +66 82 106 5316</Text>
            <Text>Address: cocotravelagc</Text>
          </Stack>
        </SimpleGrid>

        {/* Copyright */}
        <Text mt={10} pt={6} borderTop="1px" borderColor="gray.200" textAlign="center">
          © {new Date().getFullYear()} Coco Travel. All rights reserved.
        </Text>
      </Container>
    </Box>
  )
}

export default Footer