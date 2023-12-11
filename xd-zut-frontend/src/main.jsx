import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.jsx'
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { MantineProvider } from '@mantine/core'
import Form from './routes/Opinion.jsx'
import RootLayout from './layout/RootLayout.jsx'
import Login from './routes/Login.jsx'

const router = createBrowserRouter([
  {
    path: '/',
    element: <RootLayout />,
    children: [
      {
        path: '/opinion/:roomNumber',
        element: <Form />,
      },
    ],
  },
  {
    path: '/login',
    element: <Login />,
  },
])

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <MantineProvider withGlobalStyles withNormalizeCSS>
      <RouterProvider router={router} />
      {/* <App /> */}
    </MantineProvider>
  </React.StrictMode>
)
