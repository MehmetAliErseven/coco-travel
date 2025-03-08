import { useRef } from 'react'
import { useTranslation } from 'react-i18next'
import {
  useDisclosure,
  AlertDialog,
  AlertDialogBody,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogContent,
  AlertDialogOverlay,
  Button,
} from '@chakra-ui/react'

export const useLogoutConfirm = (onConfirm) => {
  const { isOpen, onOpen, onClose } = useDisclosure()
  const cancelRef = useRef()
  const { t } = useTranslation()

  const LogoutConfirmDialog = () => (
    <AlertDialog
      isOpen={isOpen}
      leastDestructiveRef={cancelRef}
      onClose={onClose}
    >
      <AlertDialogOverlay>
        <AlertDialogContent>
          <AlertDialogHeader>
            {t('admin.logout.confirmTitle')}
          </AlertDialogHeader>

          <AlertDialogBody>
            {t('admin.logout.confirmMessage')}
          </AlertDialogBody>

          <AlertDialogFooter>
            <Button ref={cancelRef} onClick={onClose}>
              {t('admin.actions.cancel')}
            </Button>
            <Button
              colorScheme="red"
              onClick={() => {
                onClose()
                onConfirm()
              }}
              ml={3}
            >
              {t('admin.logout.confirm')}
            </Button>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialogOverlay>
    </AlertDialog>
  )

  return {
    LogoutConfirmDialog,
    onOpen
  }
}