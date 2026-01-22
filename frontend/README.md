# Vue 3 Frontend for Website Uptime Monitoring

This is the frontend application built with Vue 3, TypeScript, and Vite, designed to work with the Laravel API backend.

## Tech Stack

- **Vue 3** - Progressive JavaScript framework
- **JavaScript** - Modern ES6+ JavaScript
- **Vite** - Fast build tool and dev server
- **Vue Router** - Official routing library
- **Pinia** - State management
- **Axios** - HTTP client for API communication
- **Vitest** - Unit testing framework
- **ESLint + Prettier** - Code quality and formatting

## Project Setup

### Prerequisites

- Node.js (v18 or higher recommended)
- npm or yarn
- Laravel backend running on `http://localhost:8000`

### Installation

```bash
npm install
```

### Environment Configuration

Create a `.env` file in the frontend directory (already created):

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

This tells the frontend where to find your Laravel API.

### Development Server

Start the development server:

```bash
npm run dev
```

The application will be available at `http://localhost:5173`

### Other Commands

```bash
# Build for production
npm run build

# Run unit tests with Vitest
npm run test:unit

# Lint and fix files
npm run lint

# Format code with Prettier
npm run format
```

## API Integration

### API Service Configuration

The API client is configured in [src/services/api.ts](src/services/api.ts) with:

- Base URL from environment variables
- Automatic Bearer token authentication
- Request/response interceptors
- Error handling with automatic redirect on 401

### Using the API Service

Example usage in your components:

```javascript
import { exampleService } from '@/services/exampleService'

// In your component
const fetchData = async () => {
  try {
    const data = await exampleService.getItems()
    console.log(data)
  } catch (error) {
    console.error('API Error:', error)
  }
}
```

### Creating New API Services

Create service files in `src/services/` directory:

```javascript
import api from './api'

export const yourService = {
  getResource: async () => {
    const response = await api.get('/your-endpoint')
    return response.data
  },

  createResource: async (data) => {
    const response = await api.post('/your-endpoint', data)
    return response.data
  }
}
```

## Project Structure

```
frontend/
├── src/
│   ├── assets/          # Static assets (images, styles)
│   ├── components/      # Vue components
│   │   └── ApiTest.vue  # API connection test component
│   ├── router/          # Vue Router configuration
│   ├── services/        # API services
│   │   ├── api.ts       # Axios instance configuration
│   │   └── exampleService.ts  # Example API service
│   ├── stores/          # Pinia stores
│   ├── views/           # Page components
│   ├── App.vue          # Root component
│   └── main.ts          # Application entry point
├── .env                 # Environment variables
├── .env.example         # Environment variables template
└── vite.config.ts       # Vite configuration
```

## Connecting to Laravel Backend

### Backend Requirements

1. Laravel backend should be running on `http://localhost:8000`
2. CORS is configured in Laravel to accept requests from `http://localhost:5173`
3. Laravel Sanctum is configured for API authentication

### Backend Environment Variables

Ensure your Laravel `.env` file has:

```env
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
```

### Testing the Connection

The home page includes an API Test component that automatically tests the connection to your Laravel backend. Visit `http://localhost:5173` after starting both servers to see the connection status.

## Authentication

The API service is pre-configured to handle Bearer token authentication:

1. Store the token in localStorage after login:
   ```javascript
   localStorage.setItem('auth_token', token)
   ```

2. The token is automatically included in all API requests via the request interceptor

3. On 401 responses, the user is automatically redirected to `/login` and the token is cleared

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Vue - Official](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (previously Volar)

## Recommended Browser Setup

- Chromium-based browsers (Chrome, Edge, Brave, etc.):
  - [Vue.js devtools](https://chromewebstore.google.com/detail/vuejs-devtools/nhdogjmejiglipccpnnnanhbledajbpd)
  - [Turn on Custom Object Formatter in Chrome DevTools](http://bit.ly/object-formatters)
- Firefox:
  - [Vue.js devtools](https://addons.mozilla.org/en-US/firefox/addon/vue-js-devtools/)
  - [Turn on Custom Object Formatter in Firefox DevTools](https://fxdx.dev/firefox-devtools-custom-object-formatters/)

## Additional Resources

- [Vue 3 Documentation](https://vuejs.org/)
- [Vite Documentation](https://vitejs.dev/)
- [Vue Router Documentation](https://router.vuejs.org/)
- [Pinia Documentation](https://pinia.vuejs.org/)
- [Axios Documentation](https://axios-http.com/)
