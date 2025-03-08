import { extendTheme } from '@chakra-ui/react'
import { colors } from './colors'
import { components } from './components'
import { fonts } from './foundations'

const theme = extendTheme({
  colors,
  fonts,
  components,
  styles: {
    global: {
      body: {
        bg: 'gray.50',
      }
    }
  }
})

export default theme