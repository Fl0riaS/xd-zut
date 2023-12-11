import { Badge, Box, Paper, Stack, Text, Title } from '@mantine/core'
import { useParams } from 'react-router-dom'
import dayjs from 'dayjs'

const DUMMY_DATA = {
  date: '2021-05-05',
  totalScore: 5,
  monthlyScore: 5,
  generate_in: 12897312676839,
  course: {
    subject: {
      title: 'Programowanie aplikacji internetowych',
    },
  },
  opinions: [
    {
      id: 1,
      score: 0,
      comment: 'Bardzo fajny pokój',
      created_at: '2021-05-05',
    },
    {
      id: 2,
      score: 0,
      comment: 'Bardzo fajny pokój',
      created_at: '2021-05-05',
    },
    {
      id: 3,
      score: 1,
      comment: 'Bardzo fajny pokój',
      created_at: '2021-05-05',
    },
    {
      id: 4,
      score: 0,
      comment: 'Bardzo fajny pokój',
      created_at: '2021-05-05',
    },
    {
      id: 5,
      score: 1,
      comment: 'Bardzo fajny pokój',
      created_at: '2021-05-05',
    },
  ],
}

const ReportPage = () => {
  const { reportId } = useParams()

  return (
    <Box p='xl'>
      <Title order={1}>
        Raport:{' '}
        <Text
          span
          variant='gradient'
          gradient={{ from: 'indigo', to: 'cyan', deg: 45 }}
          sx={{ fontFamily: 'Greycliff CF, sans-serif' }}
          fw={700}
        >
          {reportId}
        </Text>
      </Title>

      <Title order={3}>
        Utworzony:{' '}
        <Text span c='blue' inherit>
          {dayjs(DUMMY_DATA.date).format('DD.MM.YYYY')}
        </Text>{' '}
      </Title>

      <Title order={3}>
        Wszystkie punkty:{' '}
        <Text span c='blue' inherit>
          {DUMMY_DATA.totalScore}
        </Text>{' '}
      </Title>
      <Title order={3}>
        Punkty w tym miesiącu:{' '}
        <Text span c='blue' inherit>
          {DUMMY_DATA.monthlyScore}
        </Text>{' '}
      </Title>

      <Title order={3}>
        Kurs:{' '}
        <Text span c='blue' inherit>
          {DUMMY_DATA.course.subject.title}
        </Text>{' '}
      </Title>
      <Stack spacing='md' mt='md'>
        {DUMMY_DATA.opinions.map(opinion => (
          <Paper withBorder shadow='md' key={opinion.id} p='md'>
            <Title order={4}>
              Ocena:{' '}
              <Badge
                color={opinion.score === 0 ? 'red' : 'green'}
                variant='filled'
              >
                {opinion.score ? 'Dobrze' : 'Źle'}
              </Badge>
            </Title>
            <Title order={4}>
              Komentarz:{' '}
              <Text span c='blue' inherit>
                {opinion.comment}
              </Text>{' '}
            </Title>
            <Title order={4}>
              Utworzony:{' '}
              <Text span c='blue' inherit>
                {dayjs(opinion.created_at).format('DD.MM.YYYY')}
              </Text>{' '}
            </Title>
          </Paper>
        ))}
      </Stack>
    </Box>
  )
}

export default ReportPage
