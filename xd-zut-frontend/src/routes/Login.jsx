import { Button, Center, Input, Stack, Text } from '@mantine/core'
import { IconLock, IconUser } from '@tabler/icons-react'

const Login = () => {
  const handleLogin = () => {
    window.alert('Logowanie bedzie jak yuras skonczy backend')
  }

  return (
    <Center>
      <Stack p='md' w='60%'>
        <Text>Logowanie</Text>
        <Input icon={<IconUser />} placeholder='Login...' />
        <Input icon={<IconLock />} type='password' placeholder='********' />
        <Button variant='light' onClick={() => handleLogin()}>
          Zaloguj
        </Button>
      </Stack>
    </Center>
  )
}

export default Login
