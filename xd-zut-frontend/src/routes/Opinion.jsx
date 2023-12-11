import {
  ActionIcon,
  Button,
  Flex,
  Stack,
  TextInput,
  Textarea,
  Title,
  UnstyledButton,
} from '@mantine/core'
import { IconMoodHappy, IconMoodSad } from '@tabler/icons-react'
import { useState } from 'react'

function Opinion() {
  const [rate, setRate] = useState()

  return (
    <Stack w='100%' h='100%' align='center' justify='center' spacing='xl'>
      <Title>Jak podobały ci się zajęcia?</Title>
      <Flex w='100%' justify='center' gap='xl'>
        <Button h='10rem' w='10rem' variant='light' color='red'>
          <IconMoodSad
            size='10rem'
            onClick={() => {
              setRate(-1)
            }}
          />
        </Button>
        <Button h='10rem' w='10rem' variant='light' color='green'>
          <IconMoodHappy
            size='10rem'
            onClick={() => {
              setRate(1)
            }}
          />
        </Button>
      </Flex>
      {rate && (
        <>
          <Textarea
            label='Dodaj komentarz do oceny'
            placeholder='Komentarz'
            variant='filled'
            radius='lg'
            size='lg'
            minRows={6}
            maxRows={6}
            p={'xl'}
            w='80%'
          />
          <Flex gap='xl'>
            <Button size='xl' variant='outline' miw='15rem'>
              Zmień ocenę
            </Button>
            <Button size='xl' miw='15rem'>
              Wyślij
            </Button>
          </Flex>
        </>
      )}
    </Stack>
  )
}

export default Opinion
