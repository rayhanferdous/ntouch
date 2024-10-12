import express from 'express';
import fetch from 'node-fetch';
import cors from 'cors';

const app = express();
const port = 3000;

app.use(cors('http://0.0.0.0:8000/')); // Enable CORS for all routes

app.get('/api/menu', async (req, res) => {
    const apiKey = 'JFySrN9UWK1qzGq4KkgWepVt7y4QgcDz';
    const apiUrl = 'https://onlineorderingsecure.com/api/1.0/restaurant/19034/menu';

    try {
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json', // Optional, but often good to include
                'api-key': apiKey // Add the API key here
            }
        });

        // Log the response status for debugging
        console.log('Response Status:', response.status);

        if (!response.ok) {
            // Log the response body for further details
            const errorBody = await response.text();
            console.error('Error Body:', errorBody);
            return res.status(response.status).json({ error: 'Failed to fetch data', details: errorBody });
        }

        const data = await response.json();
        res.json(data);
    } catch (error) {
        console.error('Fetch Error:', error); // Log the error for debugging
        res.status(500).json({ error: 'Failed to fetch data', details: error.message });
    }
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});

