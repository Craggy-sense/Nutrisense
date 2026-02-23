/**
 * API UTILITY: This file acts as a "Bridge" between the HTML pages and the Laravel Backend.
 * It handles the technical details of sending network requests (fetch) and managing security tokens.
 */
const API_BASE_URL = 'http://127.0.0.1:8000/api';

const api = {
    // ---- MEAL ACTIONS ----
    meals: {
        getToday: () => request('/meals'), // Fetches all meals for today
        add: (data) => request('/meals', { method: 'POST', body: data }), // Sends a new meal to the DB
        'delete': (id) => request(`/meals/${id}`, { method: 'DELETE' }), // Deletes a specific meal (quoted to avoid reserved word conflict)
        clear: () => request('/meals/clear', { method: 'POST' }) // Wipes all of today's meals
    },

    // ---- WATER ACTIONS ----
    water: {
        getToday: () => request('/water'), // Gets your current water count
        store: (glasses) => request('/water', { method: 'POST', body: { glasses } }) // Updates your water count
    },

    // ---- AI CHAT ----
    ai: {
        chat: (message, context) => request('/ai/chat', {
            method: 'POST',
            body: { message, context }
        }) // Sends your chat message to the AI proxy in the backend
    },

    // ---- AUTHENTICATION ----
    auth: {
        register: (data) => request('/auth/register', { method: 'POST', body: data }),
        login: (data) => request('/auth/login', { method: 'POST', body: data })
    }
};

/**
 * request(): A helper function that handles all the heavy lifting for network calls.
 * It automatically adds your security token (ns_token) to every request
 * so the backend knows which user is logged in.
 */
async function request(path, options = {}) {
    // 1. Get the security token from your browser's local storage
    const token = localStorage.getItem('ns_token');

    // 2. Setup headers (meta-information about the request)
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    // 3. If we have a token, add it to the 'Authorization' header
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    // 4. Send the request to the backend using the built-in 'fetch' function
    const response = await fetch(`${API_BASE_URL}${path}`, {
        ...options,
        headers: { ...headers, ...options.headers },
        body: options.body ? JSON.stringify(options.body) : undefined
    });

    // 5. Handle security errors (Session Expired/Not Logged In)
    if (response.status === 401) {
        localStorage.removeItem('ns_token');
        sessionStorage.removeItem('ns_user');
        // Redirect to login if we are not already on a public page
        if (!window.location.pathname.endsWith('login.html') && !window.location.pathname.endsWith('index.html')) {
            window.location.href = 'login.html';
        }
    }

    // 6. Return the data from the backend as a standard JavaScript object
    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new Error(data.message || data.error || 'Network response was not ok');
    }

    return data;
}
