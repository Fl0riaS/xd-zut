import { useParams } from 'react-router-dom'

const Opinion = () => {
  // Get id from param
  const { roomNumber } = useParams()

  return (
    <div>
      <h1>Form</h1>
      <p>Course id: {roomNumber}</p>
    </div>
  )
}

export default Opinion
