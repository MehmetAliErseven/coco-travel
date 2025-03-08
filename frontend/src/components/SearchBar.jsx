import {
  Box,
  Input,
  InputGroup,
  InputLeftElement,
  Select,
  Stack,
  Button,
} from '@chakra-ui/react'
import { FaSearch } from 'react-icons/fa'
import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

const SearchBar = ({ categories, variant = "normal" }) => {
  const navigate = useNavigate()
  const [searchParams, setSearchParams] = useState({
    query: '',
    category: ''
  })

  const handleSearch = (e) => {
    e.preventDefault()
    const params = new URLSearchParams()
    if (searchParams.query) params.append('q', searchParams.query)
    if (searchParams.category) params.append('category', searchParams.category)
    navigate(`/tours/search?${params.toString()}`)
  }

  const handleChange = (e) => {
    const { name, value } = e.target
    setSearchParams(prev => ({
      ...prev,
      [name]: value
    }))
  }

  if (variant === "hero") {
    return (
      <Box as="form" onSubmit={handleSearch} width="100%" maxW="800px">
        <Stack
          direction={{ base: 'column', md: 'row' }}
          spacing={4}
          bg="white"
          p={4}
          borderRadius="lg"
          shadow="lg"
        >
          <InputGroup size="lg">
            <InputLeftElement pointerEvents="none">
              <FaSearch color="gray.300" />
            </InputLeftElement>
            <Input
              name="query"
              placeholder="Search tours..."
              value={searchParams.query}
              onChange={handleChange}
            />
          </InputGroup>

          <Select
            name="category"
            placeholder="All Categories"
            value={searchParams.category}
            onChange={handleChange}
            size="lg"
            maxW={{ base: "100%", md: "200px" }}
          >
            {categories?.map((category) => (
              <option key={category.id} value={category.slug}>
                {category.name}
              </option>
            ))}
          </Select>

          <Button type="submit" colorScheme="blue" size="lg" px={8}>
            Search
          </Button>
        </Stack>
      </Box>
    )
  }

  return (
    <Box as="form" onSubmit={handleSearch}>
      <Stack direction="row" spacing={4}>
        <InputGroup>
          <InputLeftElement pointerEvents="none">
            <FaSearch color="gray.300" />
          </InputLeftElement>
          <Input
            name="query"
            placeholder="Search tours..."
            value={searchParams.query}
            onChange={handleChange}
          />
        </InputGroup>

        <Select
          name="category"
          placeholder="All Categories"
          value={searchParams.category}
          onChange={handleChange}
          maxW="200px"
        >
          {categories?.map((category) => (
            <option key={category.id} value={category.slug}>
              {category.name}
            </option>
          ))}
        </Select>

        <Button type="submit" colorScheme="blue">
          Search
        </Button>
      </Stack>
    </Box>
  )
}

export default SearchBar