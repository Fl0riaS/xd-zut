import {
  ActionIcon,
  Box,
  Flex,
  Pagination,
  Paper,
  Stack,
  Text,
  Title,
} from '@mantine/core'
import dayjs from 'dayjs'
import { IconExternalLink, IconSend } from '@tabler/icons-react'
import { useNavigate } from 'react-router-dom'
import { useState } from 'react'

const DUMMY_DATA = [
  {
    reportId: 1,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 2,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 3,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 4,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 5,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 6,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 7,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 8,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 9,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 10,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 11,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 12,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 13,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 14,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 15,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 16,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 17,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 18,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 19,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie aplikacji internetowych',
  },
  {
    reportId: 20,
    date: '2021-05-05',
    teacher: 'Jan Kowalski',
    subject: 'Programowanie',
  },
]

const ReportListPage = () => {
  const navigate = useNavigate()

  const [activePage, setActivePage] = useState(1)

  const handleResend = () => {
    window.alert('resend')
  }

  const handleOpenReport = ({ id }) => {
    navigate(`/report/${id}`)
  }

  const chunks = DUMMY_DATA.reduce((resultArray, item, index) => {
    const chunkIndex = Math.floor(index / 10)

    if (!resultArray[chunkIndex]) {
      resultArray[chunkIndex] = [] // start a new chunk
    }

    resultArray[chunkIndex].push(item)

    return resultArray
  }, [])

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
      <Stack spacing='md' mt='md'>
        {chunks[activePage - 1].map(report => (
          <Paper withBorder shadow='md' key={report.id} p='md'>
            <Flex align='center'>
              <Box
                sx={{
                  flexGrow: 1,
                }}
              >
                <Title order={4}>
                  Prowadzący:{' '}
                  <Text span c='blue' inherit>
                    {report.teacher}
                  </Text>{' '}
                </Title>
                <Title order={4}>
                  Utworzony:{' '}
                  <Text span c='blue' inherit>
                    {dayjs(report.date).format('DD.MM.YYYY')}
                  </Text>{' '}
                </Title>
                <Title order={4}>
                  Przedmiot:{' '}
                  <Text span c='blue' inherit>
                    {report.subject}
                  </Text>{' '}
                </Title>
              </Box>
              <Flex>
                <ActionIcon size='xl' onClick={() => handleResend()}>
                  <IconSend />
                </ActionIcon>
                <ActionIcon
                  size='xl'
                  onClick={() => handleOpenReport({ id: report.reportId })}
                >
                  <IconExternalLink />
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
