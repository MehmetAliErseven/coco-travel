import { Component } from 'react'
import {
  Box,
  Button,
  Container,
  Heading,
  Text,
  VStack,
} from '@chakra-ui/react'

class ErrorBoundary extends Component {
  constructor(props) {
    super(props)
    this.state = { hasError: false, error: null }
  }

  static getDerivedStateFromError(error) {
    return { hasError: true, error }
  }

  componentDidCatch(error, errorInfo) {
    // You can log the error to an error reporting service here
    console.error('Error caught by boundary:', error, errorInfo)
  }

  handleRetry = () => {
    this.setState({ hasError: false, error: null })
    window.location.reload()
  }

  render() {
    if (this.state.hasError) {
      return (
        <Container maxW="container.md" py={10}>
          <VStack spacing={6} align="center" textAlign="center">
            <Heading>Oops! Something went wrong</Heading>
            <Text color="gray.600">
              We apologize for the inconvenience. Please try refreshing the page.
            </Text>
            <Box>
              <Button
                colorScheme="blue"
                onClick={this.handleRetry}
              >
                Retry
              </Button>
            </Box>
            {process.env.NODE_ENV === 'development' && (
              <Box
                mt={4}
                p={4}
                bg="red.50"
                color="red.900"
                borderRadius="md"
                width="100%"
                overflowX="auto"
              >
                <Text fontFamily="monospace" fontSize="sm" whiteSpace="pre-wrap">
                  {this.state.error?.toString()}
                </Text>
              </Box>
            )}
          </VStack>
        </Container>
      )
    }

    return this.props.children
  }
}

export default ErrorBoundary