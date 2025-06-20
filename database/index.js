const express = require('express');
const cors = require('cors');
const app = express();

app.use(cors()); // Enable CORS for all requests, or specify options
// app.use(cors({ origin: 'http://localhost:8080' }));

app.post('/api/register', (req, res) => {
  // ... your registration logic
  res.json({ message: 'User registered successfully' });
});

app.listen(8000, () => console.log('Backend running on port 8000'));