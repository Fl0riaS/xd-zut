import React from 'react'
import ReactDOM from 'react-dom/client'
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { MantineProvider } from '@mantine/core'
import Form from './routes/Opinion.jsx'
import RootLayout from './layout/RootLayout.jsx'
import './main.css'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import Login from './routes/Login.jsx'
import ReportPage from './pages/ReportPage.jsx'
import ReportListPage from './routes/Reports.jsx'

const queryClient = new QueryClient()

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
  {
    path: '/report/:reportId',
    element: <ReportPage />,
  },
  {
    path: '/reports',
    element: <ReportListPage />,
  },
])

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <MantineProvider
        withGlobalStyles
        withNormalizeCSS
        theme={{ colorScheme: 'dark' }}
      >
        <RouterProvider router={router} />
      </MantineProvider>
    </QueryClientProvider>
  </React.StrictMode>
)
