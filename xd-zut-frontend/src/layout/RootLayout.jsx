import { Outlet } from 'react-router-dom'

const RootLayout = () => {
  return (
    <div>
      <h1>Root layout</h1>
      <nav>
        <ul>
          <li>
            <a href='/'>Home</a>
          </li>
          <li>
            <a href='/opinion/1'>Form 1</a>
          </li>
          <li>
            <a href='/opinion/2'>Form 2</a>
          </li>
        </ul>
      </nav>
      <Outlet />
    </div>
  )
}

export default RootLayout
