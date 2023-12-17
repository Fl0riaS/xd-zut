import {
  ActionIcon,
  Box,
  Divider,
  Flex,
  Loader,
  Pagination,
  Paper,
  Stack,
  Text,
  TextInput,
  Title,
} from '@mantine/core'
import dayjs from 'dayjs'
import { IconDownload, IconSend } from '@tabler/icons-react'
import { useNavigate } from 'react-router-dom'
import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import { DatePickerInput } from '@mantine/dates'

const ReportListPage = () => {
  const navigate = useNavigate()

  const [activePage, setActivePage] = useState(1)
  const [chosenDate, setChosenDate] = useState([])
  const [teacherName, setTeacherName] = useState()
  const [subjectName, setSubjectName] = useState()

  const handleResend = () => {
    window.alert('resend')
  }

  const handleOpenReport = ({ id }) => {
    navigate(`/report/${id}`)
  }

  const { isLoading, data } = useQuery({
    queryKey: ['raports'],
    queryFn: async () => {
      const response = await fetch('http://localhost:3000/raports')
      return await response.json()
    },
  })

  const chunks = data
    ?.filter(report => {
      if (
        chosenDate[0] &&
        chosenDate[1] &&
        (dayjs(report.date.date) < dayjs(chosenDate[0]) ||
          dayjs(report.date.date) > dayjs(chosenDate[1]))
      ) {
        return false
      }

      // TODO: filter by teacher and subject

      return true
    })
    .reduce((resultArray, item, index) => {
      const chunkIndex = Math.floor(index / 10)

      if (!resultArray[chunkIndex]) {
        resultArray[chunkIndex] = [] // start a new chunk
      }

      resultArray[chunkIndex].push(item)

      return resultArray
    }, [])

  if (isLoading && !data) {
    return (
      <Flex h='100%' w='100%' justify='center' align='center'>
        <Loader size='xl' />
      </Flex>
    )
  }

  return (
    <Box p='xl'>
      <Title order={1}>
        <Text
          span
          variant='gradient'
          gradient={{ from: 'indigo', to: 'cyan', deg: 45 }}
          sx={{ fontFamily: 'Greycliff CF, sans-serif' }}
          fw={700}
        >
          Lista raportów
        </Text>
      </Title>
      <Divider my='xl' />
      <Flex justify='space-around'>
        <DatePickerInput
          type='range'
          label='Data zajęć'
          placeholder='Wybierz daty'
          value={chosenDate}
          onChange={setChosenDate}
          miw={400}
        />
        <TextInput
          label='Prowadzący'
          value={teacherName}
          onChange={setTeacherName}
          miw={400}
          placeholder='Wpisz imię i/lub nazwisko prowadzącego'
        />
        <TextInput
          label='Przedmiot'
          value={subjectName}
          onChange={setSubjectName}
          miw={400}
          placeholder='Wpisz nazwę przedmiotu'
        />
      </Flex>
      <Divider my='xl' />
      <Stack spacing='md' mt='md'>
        {chunks.length === 0 && (
          <Text align='center'>Nie znaleziono zajęć dla twoich filtrów</Text>
        )}
        {chunks.length > 0 &&
          chunks[activePage - 1].map(report => (
            <Paper withBorder shadow='md' key={report.id} p='md'>
              <Flex align='center'>
                <Box
                  sx={{
                    flexGrow: 1,
                  }}
                >
                  <Title order={4}>
                    Prowadzący:
                    <Text span c='blue' inherit>
                      {report.teacher}
                    </Text>
                  </Title>
                  <Title order={4}>
                    Utworzony:
                    <Text span c='blue' inherit>
                      {` ${dayjs(report.date.date).format('DD.MM.YYYY')}`}
                    </Text>
                  </Title>
                  <Title order={4}>
                    Przedmiot:
                    <Text span c='blue' inherit>
                      {report.subject}
                    </Text>
                  </Title>
                </Box>
                <Flex>
                  <ActionIcon size='xl' onClick={handleResend}>
                    <IconSend />
                  </ActionIcon>
                  <ActionIcon
                    size='xl'
                    onClick={() => handleOpenReport({ id: report.reportId })}
                  >
                    <IconDownload />
                  </ActionIcon>
                </Flex>
              </Flex>
            </Paper>
          ))}
        <Pagination
          total={chunks.length}
          grow
          value={activePage}
          onChange={setActivePage}
        />
      </Stack>
    </Box>
  )
}

export default ReportListPage