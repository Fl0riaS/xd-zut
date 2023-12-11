import { Button, Center } from '@mantine/core'
import { Link, Outlet } from 'react-router-dom'

const RootLayout = () => {
  return <Outlet />

  return (
    <div>
      <Center mih='100vh'>
        <Link to='/login'>
          <Button variant='light'>Zaloguj</Button>
        </Link>
      </Center>
      <Outlet />
    </div>
  )
}

export default RootLayout
