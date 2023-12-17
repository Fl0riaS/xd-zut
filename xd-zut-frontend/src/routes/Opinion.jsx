import {
  Button,
  Divider,
  Flex,
  Loader,
  Stack,
  Text,
  Textarea,
  Title,
} from '@mantine/core'
import { IconMoodHappy, IconMoodSad } from '@tabler/icons-react'
import { useMutation, useQuery } from '@tanstack/react-query'
import dayjs from 'dayjs'
import { useState } from 'react'
import { useParams } from 'react-router-dom'
import { useLocalStorage } from '@mantine/hooks'

function Opinion() {
  // #region HOOKS
  const { roomNumber } = useParams()
  const [ratedLessons, setRatedLessons] = useLocalStorage({
    key: 'rated-lessons',
    defaultValue: [],
  })

  const [rate, setRate] = useState(0)
  const [rateDescription, setRateDescription] = useState('')
  // #endregion

  // #region MUTATIONS
  const { isLoading, data } = useQuery({
    queryKey: ['course', roomNumber],
    queryFn: async () => {
      const startDate = dayjs().subtract(3, 'd').format()
      const endDate = dayjs().add(1, 'd').format()

      const response = await fetch(
        `https://api.allorigins.win/get?url=${encodeURIComponent(
          `https://plan.zut.edu.pl/schedule_student.php?room=${roomNumber}&start=${startDate}&end=${endDate}`
        )}`
      )
      const { contents } = await response.json()
      const lessons = await JSON.parse(contents)
      console.log(lessons)
      // For some reason ZUT decided to return empty array on first index XDDDD
      lessons.shift()

      const result = lessons[0]
      // const result = lessons.find(
      //   lesson => dayjs().add(30, 'm') < dayjs(lesson.end)
      // )

      return result
    },
  })

  const addOpinionMutation = useMutation({
    mutationFn: async data => {
      const response = await fetch('http://localhost:3000/opinion/add', {
        method: 'POST',
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
        mode: 'cors', // no-cors, *cors, same-origin
      })
      return response.json()
    },
  })
  // #endregion

  // #region HANDLERS
  const handleSubmit = async () => {
    const mutationData = {
      score: rate,
      startDate: data.start,
      endDate: data.end,
      workerTitle: data.worker_title,
      title: data.title,
      groupName: data.group_name,
      comment: rateDescription,
    }

    // send to backend
    await addOpinionMutation.mutateAsync(mutationData)

    // Add rated class to local storage
    setRatedLessons(previouslyRatedClasses => [
      ...previouslyRatedClasses,
      { roomNumber, startDate: data.start },
    ])
  }
  // #endregion

  // #region JSX
  if (isLoading) {
    return (
      <Flex w='100%' h='100%' justify='center' align='center'>
        <Loader size='xl' />
      </Flex>
    )
  }

  if (!data) {
    return (
      <Stack w='100%' h='100%' justify='center' align='center'>
        <IconMoodSad size='8rem' />
        <Title align='center' px='sm'>
          Nie znaleziono zajęć w tej sali
        </Title>
      </Stack>
    )
  }

  // Check if current lesson is not already rated by user
  if (
    ratedLessons.find(
      lesson =>
        lesson.roomNumber === roomNumber && data.start === lesson.startDate
    )
  ) {
    return (
      <Stack w='100%' h='100%' justify='center' align='center'>
        <IconMoodHappy size='8rem' />
        <Title align='center' px='sm'>
          Pomyślnie przesłano ocenę
        </Title>
      </Stack>
    )
  }

  return (
    <Stack
      w='100%'
      h='100%'
      align='center'
      justify='center'
      spacing='xl'
      px='md'
    >
      <Title align='center'>Jak podobały ci się zajęcia?</Title>
      <Text align='center'>{data.description}</Text>
      <Divider h='1rem' w='100%' size='md' />
      <Flex w='100%' justify='center' gap='xl'>
        {rate <= 0 && (
          <Button
            h='10rem'
            w='10rem'
            variant='light'
            color='red'
            onClick={() => {
              setRate(-1)
            }}
          >
            <IconMoodSad size='10rem' />
          </Button>
        )}
        {rate >= 0 && (
          <Button
            h='10rem'
            w='10rem'
            variant='light'
            color='green'
            onClick={() => {
              setRate(1)
            }}
          >
            <IconMoodHappy size='10rem' />
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
            p='xl'
            w='80%'
            value={rateDescription}
            onChange={e => setRateDescription(e.target.value)}
          />
          <Flex gap='xl' w='100%' justify='space-around'>
            <Button
              size='md'
              variant='outline'
              onClick={() => setRate(0)}
              miw='10rem'
              disabled={addOpinionMutation.isLoading}
            >
              Zmień ocenę
            </Button>
            <Button
              size='md'
              onClick={handleSubmit}
              miw='10rem'
              loading={addOpinionMutation.isLoading}
            >
              Wyślij
            </Button>
          </Flex>
        </>
      )}
    </Stack>
  )
  // #endregion
}

export default Opinion
