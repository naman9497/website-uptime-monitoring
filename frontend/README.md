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

### Installation

```bash
npm install
```

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

## Connecting to Laravel Backend

1. Laravel backend should be running on `http://localhost/api/v1`
2. CORS is configured in Laravel to accept requests from `http://localhost:5173`

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
