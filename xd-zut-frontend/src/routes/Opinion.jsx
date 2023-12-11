import { Button, Flex, Stack, Textarea, Title } from '@mantine/core'
import { IconMoodHappy, IconMoodSad } from '@tabler/icons-react'
import { useState } from 'react'
import { useParams } from 'react-router-dom'

function Opinion() {
  const { roomNumber } = useParams()
  const [rate, setRate] = useState(0)

  const handleSubmit = () => {}

  return (
    <Stack w='100%' h='100%' align='center' justify='center' spacing='xl'>
      <Title align='center'>Jak podobały ci się zajęcia?</Title>
      <Flex w='100%' justify='center' gap='xl'>
        {rate <= 0 && (
          <Button h='10rem' w='10rem' variant='light' color='red'>
            <IconMoodSad
              size='10rem'
              onClick={() => {
                setRate(-1)
              }}
            />
          </Button>
        )}
        {rate >= 0 && (
          <Button h='10rem' w='10rem' variant='light' color='green'>
            <IconMoodHappy
              size='10rem'
              onClick={() => {
                setRate(1)
              }}
            />
          </Button>
        )}
      </Flex>
      {rate !== 0 && (
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
          <Flex gap='xl' w='100%' justify='space-around'>
            <Button
              size='md'
              variant='outline'
              onClick={() => setRate(0)}
              miw='10rem'
            >
              Zmień ocenę
            </Button>
            <Button size='md' onClick={handleSubmit} miw='10rem'>
              Wyślij
            </Button>
          </Flex>
        </>
      )}
    </Stack>
  )
}

export default Opinion
