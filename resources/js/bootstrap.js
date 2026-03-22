import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { Ziggy } from './ziggy';
window.route = (name, params, absolute) => {
    const route = Ziggy.routes[name];
    if (!route) {
        console.error(`Route "${name}" not found`);
        return '#';
    }

    let uri = route.uri;

    // Replace parameters
    if (params) {
        if (typeof params === 'object') {
            Object.keys(params).forEach(key => {
                uri = uri.replace(`{${key}}`, params[key]);
            });
        } else {
            // If params is not an object, treat as single parameter
            const firstParam = Object.keys(route.bindings || {})[0];
            if (firstParam) {
                uri = uri.replace(`{${firstParam}}`, params);
            }
        }
    }

    // Remove unused parameters
    uri = uri.replace(/\{[^}]+\}/g, '');

    const baseUrl = absolute ? (Ziggy.url || '') : '';
    return baseUrl + '/' + uri;
};

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

try {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_APP_NAME?.toLowerCase() ?? 'localhost'}`,
        wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
        wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
        forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} catch (error) {
    console.warn('Failed to initialize Laravel Echo:', error);
}
