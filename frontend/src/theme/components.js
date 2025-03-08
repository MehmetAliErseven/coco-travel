export const components = {
  Button: {
    baseStyle: {
      fontWeight: 'semibold',
      borderRadius: 'md',
    },
    variants: {
      solid: (props) => ({
        bg: props.colorScheme === 'brand' ? 'brand.500' : `${props.colorScheme}.500`,
        color: 'white',
        _hover: {
          bg: props.colorScheme === 'brand' ? 'brand.600' : `${props.colorScheme}.600`,
        },
      }),
      admin: {
        bg: 'blue.500',
        color: 'white',
        _hover: {
          bg: 'blue.600',
          _disabled: {
            bg: 'blue.500'
          }
        }
      }
    },
    defaultProps: {
      colorScheme: 'brand',
    },
  },
  Card: {
    baseStyle: {
      container: {
        borderRadius: 'lg',
        boxShadow: 'sm',
        _hover: {
          transform: 'translateY(-4px)',
          transition: 'all 0.2s ease-in-out',
          boxShadow: 'md',
        },
        overflow: 'hidden',
      },
    },
    variants: {
      elevated: {
        container: {
          boxShadow: 'md',
          bg: 'white'
        }
      },
      outline: {
        container: {
          borderWidth: '1px'
        }
      }
    },
    defaultProps: {
      variant: 'elevated'
    }
  },
  Heading: {
    baseStyle: {
      fontWeight: 'bold',
      letterSpacing: 'tight',
    },
  },
  Input: {
    variants: {
      filled: {
        field: {
          borderRadius: 'md',
          bg: 'gray.50',
          _hover: {
            bg: 'gray.100',
          },
          _focus: {
            bg: 'white',
            borderColor: 'brand.500',
          },
        },
      },
    },
    defaultProps: {
      variant: 'filled',
    },
  },
  Table: {
    variants: {
      simple: {
        th: {
          borderBottom: '2px',
          borderColor: 'gray.200',
          fontSize: 'sm',
          fontWeight: 'semibold',
          textTransform: 'uppercase',
          letterSpacing: 'wider'
        },
        td: {
          borderBottom: '1px',
          borderColor: 'gray.100'
        }
      }
    }
  }
}