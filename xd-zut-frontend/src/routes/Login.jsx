import { Button, Center, Input, Stack, Text } from '@mantine/core'
import { IconLock, IconUser } from '@tabler/icons-react'
import { useRef } from 'react'

const Login = () => {
  const loginRef = useRef(null)
  const passwordRef = useRef(null)

  const handleLogin = async () => {
    // Post to localhost:3000/api/login_check

    const response = await fetch('http://localhost:3000/api/login_check', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: loginRef.current.value,
        password: passwordRef.current.value,
      }),
    })

    // set cookies

    document.cookie = 'Bearer=' + (await response.json()).token
  }

  return (
    <Center>
      <Stack p='md' w='60%'>
        <Text>Logowanie</Text>
        <Input icon={<IconUser />} placeholder='Login...' ref={loginRef} />
        <Input
          icon={<IconLock />}
          type='password'
          placeholder='********'
          ref={passwordRef}
        />
        <Button variant='light' onClick={() => handleLogin()}>
          Zaloguj
        </Button>
      </Stack>
    </Center>
  )
}

export default Login
